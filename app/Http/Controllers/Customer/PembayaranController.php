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
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    private function hitungTotalBiaya(FormPengajuan $pengajuan)
    {
        $kategori = $pengajuan->kategori;
        $parameterDipilih = $pengajuan->parameter; // parameter yang dipilih user

        $subkategori = $kategori->subkategori;

        if ($subkategori->isNotEmpty()) {
            $parameterSubkategori = $subkategori->flatMap(fn($sub) => $sub->parameter);

            $isFullSubkategoriPaket = $parameterDipilih->count() === $parameterSubkategori->count() &&
                $parameterDipilih->pluck('id')->sort()->values()->toArray() ===
                $parameterSubkategori->pluck('id')->sort()->values()->toArray();

            if ($isFullSubkategoriPaket) {
                return $kategori->harga;
            } else {
                return $parameterDipilih->sum('harga');
            }
        } else {
            $parameterKategori = $kategori->parameter;

            $isFullKategoriPaket = $parameterDipilih->count() === $parameterKategori->count() &&
                $parameterDipilih->pluck('id')->sort()->values()->toArray() ===
                $parameterKategori->pluck('id')->sort()->values()->toArray();

            if ($isFullKategoriPaket) {
                return $kategori->harga;
            } else {
                return $parameterDipilih->sum('harga');
            }
        }
    }


    public function index()
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'user'])
            ->where('id_instansi', $idInstansi)
            ->get();

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
        /** @var \App\Models\User */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan = FormPengajuan::with(['kategori', 'parameter'])
            ->where('id', $id)
            ->where('id_instansi', $idInstansi)
            ->where('status_pengajuan', 'diterima')
            ->firstOrFail();

        if ($pengajuan->status_pengajuan !== 'diterima') {
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
        /** @var \App\Models\User */
        $user = Auth::user();
        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan = FormPengajuan::with(['pembayaran', 'kategori', 'parameter'])
            ->where('id_instansi', $idInstansi)
            ->findOrFail($id);

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
        /** @var \App\Models\User */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan = FormPengajuan::where('id_instansi', $idInstansi)
            ->findOrFail($id);

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

        $idOrder = $pengajuan->pembayaran->id_order ?? 'INV-' . strtoupper(Str::random(10));

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

        if (!$pembayaran) {
            return Redirect::back()->withErrors([
                'id_order' => 'Order Gagal!, Silahkan ulangi kembali atau hubungi admin.'
            ]);
        }

        return Redirect::route('customer.pembayaran.sukses')->with('message', 'Pembayaran Berhasil.');
    }

    public function success($id)
    {
        $pembayaran = Pembayaran::with(['form_pengajuan'])->findOrFail($id);

        if ($pembayaran->form_pengajuan->instansi->id_user !== Auth::id()) {
            abort(403, 'Pastikan Anda Autentikasi Dengan User Yang Sama Dengan Akun User Yang Anda Ajukan!');
        }
        return Inertia::render('customer/pembayaran/Sukses', [
            'pembayaran' => $pembayaran
        ]);
    }
}
