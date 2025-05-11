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
use Midtrans\Notification;
use App\Mail\KonfirmasiPembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    private function hitungTotalBiaya(FormPengajuan $pengajuan)
    {
        $kategori = $pengajuan->kategori;
        $parameterDipilih = $pengajuan->parameter;
        $parameterKategori = $kategori->parameter;

        if ($parameterDipilih->count() == $parameterKategori->count() && $parameterDipilih->pluck('id')->diff($parameterKategori->pluck('id')->isEmpty())) {
            return $kategori->harga;
        } else {
            return $parameterDipilih->sum('harga');
        }
    }

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        $pengajuan = FormPengajuan::with(['kategori', 'parameter'])->get();

        $totalBiaya = $this->hitungTotalBiaya($pengajuan->id);

        return Inertia::render('customer/pembayaran/Index', [
            'pengajuan' => $pengajuan,
            'totalBiaya' => $totalBiaya,
            'detailPembayaran' => [
                'kategori' => $pengajuan->kategori,
                'parameter' => $pengajuan->parameter,
            ]
        ]);
    }

    public function show($id)
    {
        $customer = Auth::guard('customer')->user();

        $pengajuan = FormPengajuan::with(['kategori', 'parameter'])
                                    ->where('id',$id)
                                    ->where('status_pengajuan', 'diterima')
                                    ->firstOrFail();

        if($pengajuan->status_pengajuan !== 'diterima')
        {
            return Redirect::back()->withErrors([
                'status_pengajuan' => 'Pengajuan Anda Belum Diverifikasi Oleh Admin, Harap Tunggu Verifikasi Administrasi Sebelum Melakukan Pembayaran'
            ]);
        }

        $totalBiaya = $this->hitungTotalBiaya($pengajuan);

        $metode_pembayaran = ['transfer'];

        if ($pengajuan->metode_pengantaran === 'diantar') {
            $metode_pembayaran[] = 'tunai';
        }

        return Inertia::render('customer/pembayaran/Show', [
            'pengajuan' => $pengajuan,
            'totalBiaya' => $totalBiaya,
            'metodePembayaran' => $metode_pembayaran,
            'detailPembayaran' => [
                'kategori' => $pengajuan->kategori,
                'parameter' => $pengajuan->parameter
            ]
        ]);
    }

    public function upload($id)
    {
        $customer = Auth::guard('customer')->user();

        $pengajuan = FormPengajuan::with(['pembayaran', 'kategori', 'parameter'])->findOrFail($id);

        if (!$pengajuan->pembayaran || $pengajuan->pembayaran->metode_pembayaran !== 'transfer') {
            return Redirect::back();
        }

        return Inertia::render('customer/pembayaran/Upload', [
            'pengajuan' => $pengajuan,
            'pembayaran' => $pengajuan->pembayaran
        ]);
    }

    public function process($id, Request $request)
    {
        $pengajuan = FormPengajuan::findOrFail($id);
        
        if ($pengajuan->status_pengajuan !== 'diterima') {
            return Redirect::back()->withErrors([
                'status_pengajuan' => 'Harap Menunggu Verifikasi Administrasi Pengajuan Selesai Sebelum Melakukan Pembayaran'
            ]);
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|images|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validated['metode_pembayaran'] === 'transfer' && $pengajuan->metode_pengantaran !== 'diantar') {
            return Redirect::back()->withErrors([
                'metode_pembayaran' => 'Metode Pembayaran Tunai Hanya Tersedia Untuk Metode Pengantaran Diantar'
            ]);
        }

        $totalBiaya = $this->hitungTotalBiaya($pengajuan);

        $idOrder = $pengajuan->pembayaran->id_order ?? 'INV-'.strtoupper(Str::random(10));

        $data = [
            'id_order' => $idOrder,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'total_biaya' => $totalBiaya,
            'status_pembayaran' => 'diproses',
            'tanggal_pembayaran' => now()
        ];

        if ($validated['metode_pembayaran'] === 'transfer' || $request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $data['bukti_pembayaran'] = $buktiPath;
        }

        $pembayaran = Pembayaran::updateOrCreate([
            ['id_form_pengajuan' => $pengajuan],
            $data
        ]);

        return Redirect::route('customer.pembayaran.sukses')->with('message', 'Pembayaran Berhasil.');
    }

    public function success($id)
    {
        $customer = Auth::guard('customer')->user();
        
        $pengajuan = FormPengajuan::with(['pembayaran'])->findOrFail($id);       

        return Inertia::render('customer/pembayaran/Sukses', [
            'pengajuan' => $pengajuan,
            'pembayaran' => $pengajuan->pembayaran
        ]);
    }
}
