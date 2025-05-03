<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormPengajuan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Midtrans\Config;
use Midtrans\Snap;
use App\Mail\PembayaranMasuk;
use Midtrans\Notification;
use App\Mail\KonfirmasiPembayaran;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
    }

    //lihat rincian harga pengajuan customer
    public function showRincian($id)
    {
        $pengajuan = FormPengajuan::with(['kategori', 'parameter'])->findOrFail($id);

        $totalParameter = $pengajuan->parameter->sum("harga");
        $totalKategori = $pengajuan->kategori->harga ?? 0;
        $totalHarga = $totalKategori + $totalParameter;

        return Inertia::render('customer/pembayaran/Rincian', [
            'pengajuan' => $pengajuan,
            'kategori' => $pengajuan->kategori,
            'parameter' => $pengajuan->parameter,
            'totalKategori' => $totalKategori,
            'totalParameter' => $totalParameter,
            'totalHarga' => $totalHarga,
        ]);
    }

    //proses pembayaran
    public function bayarFake(Request $request, $id)
    {
        $pengajuan = FormPengajuan::findOrFail($id);

        $totalParameter = $pengajuan->parameter->sum("harga");
        $totalKategori = $pengajuan->kategori->harga ?? 0;
        $totalHarga = $totalKategori + $totalParameter;

        $midtrans = $this->createMidtransTransaction($pengajuan, $totalHarga);

        $pembayaran = Pembayaran::create([
            'id_order' => $midtrans,
            'id_form_pengajuan' => $id,
            'total_biaya' => $totalHarga,
            'tanggal_pembayaran' => now(),
            'metode_pembayaran' => $midtrans['payment_type'],
            'status_pembayaran' => 'belum_dibayar',
        ]);

        return Redirect::route('customer.pembayaran.status', ['id' => $pembayaran->id]);
    }

    //cek status pembayaran
    public function status($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $StatusMidtrans = $this->checkMidtransTransaction($pembayaran->id_order);

        $pembayaran->status_pembayaran = $StatusMidtrans['status'];
        $pembayaran->save();

        if ($StatusMidtrans['status'] === 'selesai') {
            Mail::to(Auth::guard('customer')->user()->email)->send(new PembayaranMasuk($pembayaran));
        }

        return Inertia::render('customer/pembayaran/Status', [
            'pembayaran' => $pembayaran,
            'status' => $StatusMidtrans['status'],
        ]);
    }

    public function bayar(Request $request)
    {
        $pengajuan = FormPengajuan::with(['kategori', 'parameter'])->findOrFail($request->id);

        $totalParameter = $pengajuan->parameter->sum("harga");
        $totalKategori = $pengajuan->kategori->harga ?? 0;
        $totalHarga = $totalKategori + $totalParameter;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitized');
        Config::$is3ds = config('midtrans.3ds');

        $customer = Auth::guard('customer')->user();

        $id_order = 'ORDER-' . time();
        $parameter = [
            'transaction_details' => [
                'order_id' => $id_order,
                'gross_amount' => $totalHarga,
            ],
            'customer_details' => [
                'nama' => $customer->nama,
                'email' => $customer->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($parameter);
            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $id_order,
                'gross_amount' => $totalHarga
            ]);
        } catch (\Exception $err) {
            Log::error('Tidak Bisa Mendapatkan Token', ['error' => $err->getMessage(), 'order_id' => $parameter['transaction_details'['order_id']]]);

            return Redirect::back()->withErrors([
                'Terjadi Kesalahan Saat Membuat Transaksi:'.$err->getMessage()
            ]);
        }
    }

    public function callback(Request $request)
    {
        $notif =  new Notification();
        $id_order = $notif->order_id;
        $status_code = $notif->status_code;
        $status_pembayaran = $notif->transaction_status;
        $pembayaran = Pembayaran::with('form_pengajuan.customer')->where('id_order', $id_order)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        if ($status_pembayaran == 'capture' || $status_pembayaran == 'settlement') {
            $pembayaran->status_pembayaran = 'selesai';
        } elseif ($status_pembayaran == 'pending') {
            $pembayaran->status_pembayaran = 'menunggu_konfirmasi';
        } elseif ($status_pembayaran == 'deny' || $status_pembayaran == 'expire') {
            $pembayaran->status_pembayaran = 'gagal';
        }

        Mail::to($pembayaran->form_pengajuan->customer->email)->send(new KonfirmasiPembayaran($pembayaran));

        $pembayaran->save();

        return response()->json([
            'message' => 'Notifikasi Masuk'
        ]);
    }

    private function createMidtransTransaction($pengajuan, $totalHarga)
    {
        $order = 'order_' . time();

        $transaction_details = [
            'id_order' => $order,
            'total_biaya' => $totalHarga,
        ];

        $item_details = [
            [
                'id' => $pengajuan->id,
                'total_biaya' => $totalHarga,
                'quantity' => 1,
                'name' => 'Pembayaran Pengajuan #' . $pengajuan->id,
            ]
        ];

        $customer_details =
            [
                'nama' => Auth::guard('customer')->user()->nama,
                'email' => Auth::guard('customer')->user()->email,
                'kontak' => Auth::guard('customer')->user()->kontak_pribadi,
            ];

        $transaction_details =
            [
                'transaksi_detail' => $transaction_details,
                'item_detail' => $item_details,
                'customer_detail' => $customer_details,
            ];

        $snap = new Snap();
        $response = $snap->createTransaction($transaction_details);

        return $response;
    }

    private function checkMidtransTransaction($order)
    {
        return [
            'status' => 'selesai'
        ];
    }
}
