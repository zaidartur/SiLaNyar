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


    // public function index()
    // {
    //     /** @var \App\Models\User */
    //     $user = Auth::user();

    //     $idInstansi = $user->instansi()->pluck('id')->toArray();

    //     $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'user'])
    //         ->where('id_instansi', $idInstansi)
    //         ->get();

    //     $totalBiaya = $this->hitungTotalBiaya($pengajuan->id);

    //     return Inertia::render('customer/pembayaran/Index', [
    //         'pengajuan' => $pengajuan,
    //         'totalBiaya' => $totalBiaya,
    //         'detailPembayaran' => [
    //             'kategori' => $pengajuan->kategori,
    //             'parameter' => $pengajuan->parameter,
    //         ]
    //     ]);
    // }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan = FormPengajuan::with([
            'kategori.parameter',
            'kategori.subkategori.parameter',
            'jenis_cairan',
            'pembayaran',
            'instansi.user',
        ])
            ->where('id', $id)
            ->whereIn('id_instansi', $idInstansi)
            ->firstOrFail();

        // Validasi status pengajuan harus sudah diterima
        if ($pengajuan->status_pengajuan !== 'diterima') {
            return Redirect::back()->withErrors([
                'status_pengajuan' => 'Pengajuan Anda Belum Diverifikasi Oleh Admin. Harap Tunggu Verifikasi Sebelum Melakukan Pembayaran.'
            ]);
        }

        $pembayaran = $pengajuan->pembayaran;
        $metode_pembayaran = ['transfer'];

        if ($pengajuan->metode_pengantaran === 'diantar') {
            $metode_pembayaran[] = 'tunai';
        }

        $showUploadForm = $pembayaran && $pembayaran->metode_pembayaran === 'transfer';

        return Inertia::render('customer/pembayaran/Show', [
            'pengajuan' => $pengajuan,
            'pembayaran' => $pembayaran,
            'metodePembayaran' => $metode_pembayaran,
            'detailPembayaran' => [
                'kategori' => $pengajuan->kategori,
                'parameter' => $pengajuan->parameter
            ],
            'showUploadForm' => $showUploadForm
        ]);
    }

    public function process($id, Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $tanggal = now()->format('dmY');
        $namaUser = Str::slug(strtolower($user->nama), '_');
        $randomId = Str::random(6);

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan = FormPengajuan::whereIn('id_instansi', $idInstansi)
            ->findOrFail($id);

        if ($pengajuan->status_pengajuan !== 'diterima') {
            return Redirect::back()->withErrors([
                'status_pengajuan' => 'Harap Menunggu Verifikasi Administrasi Pengajuan Selesai Sebelum Melakukan Pembayaran'
            ]);
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer',
        ]);

        if ($validated['metode_pembayaran'] === 'transfer') {
            $bukti = $request->file('bukti_pembayaran');
            $buktiFileName = 'bukti_pembayaran' . $namaUser . '_' . $tanggal . '_' . $randomId . '.' . $bukti->getClientOriginalExtension();
            $buktiPath = $bukti->storeAs('bukti_pembayaran', $buktiFileName, 'public');
            $data['bukti_pembayaran'] = $buktiPath;
        }

        if ($validated['metode_pembayaran'] === 'tunai' && $pengajuan->metode_pengantaran === 'diambil') {
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
            $bukti = $request->file('bukti_pembayaran');
            $buktiFileName = 'bukti_pembayaran' . $namaUser . '_' . $tanggal . '_' . $randomId . '.' . $bukti->getClientOriginalExtension();
            $buktiPath = $bukti->storeAs('bukti_pembayaran', $buktiFileName, 'public');
            $data['bukti_pembayaran'] = $buktiPath;
        }

        $pembayaran = Pembayaran::updateOrCreate(
            [
                'id_form_pengajuan' => $pengajuan->id
            ],
            $data
        );

        if (!$pembayaran) {
            return Redirect::back()->withErrors([
                'id_order' => 'Order Gagal!, Silahkan ulangi kembali atau hubungi admin.'
            ]);
        }

        return Redirect::route('customer.dashboard')->with('message', 'Pembayaran Berhasil.');
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
