<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\FormPengajuan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Notifications\PembayaranSukses;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['form_pengajuan.kategori.parameter', 'form_pengajuan.kategori.subkategori.parameter', 'form_pengajuan.instansi.user'])->get();

        return Inertia::render('pegawai/pembayaran/Index', [
            'pembayaran' => $pembayaran
        ]);
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['form_pengajuan.kategori.parameter', 'form_pengajuan.kategori.subkategori.parameter', 'form_pengajuan.instansi.user'])->findOrFail($id);

        return Inertia::render('pegawai/pembayaran/Detail', [
            'pembayaran' => $pembayaran
        ]);
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with(['form_pengajuan.instansi.user'])->findOrFail($id);

        return Inertia::render('pegawai/pembayaran/Edit', [
            'pembayaran' => $pembayaran
        ]);
    }

    public function update(Pembayaran $pembayaran, Request $request)
    {
        $user = Auth::user();

        $rules = [
            'status_pembayaran' => 'required|in:diproses,selesai,gagal',
            'keterangan' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules, [
            'status_pembayaran.required' => 'Status Pembayaran Wajib Diisi.',
            'status_pembayaran.in' => 'Status Pembayaran Tidak Valid.'
        ]);

        if ($validated['status_pembayaran'] === 'gagal') {
            $rules['keterangan'] = 'required|string|max:255';
        }

        if ($pembayaran->status_pembayaran === 'belum_dibayar') {
            return Redirect::back()->with('error', 'Verifikasi Pembayaran Tidak Dapat Dilakukan');
        }

        if ($pembayaran->form_pengajuan->status_pengajuan !== 'diterima') {
          return Redirect::back()->with('error', 'Verifikasi Pembayaran Hanya Bisa Dilakukan Jika Status Pengajuan Telah Diterima!');
        }
        // Only prevent verification if status is 'belum_dibayar' and trying to change to 'selesai'
        if($pembayaran->status_pembayaran === 'belum_dibayar' && $request->status_pembayaran === 'selesai') 
        {
            return Redirect::back()->with('error', 'Verifikasi Pembayaran Tidak Dapat Dilakukan');
        }

        $pembayaran->update([
            'status_pembayaran' => $validated['status_pembayaran'],
            'keterangan' => $validated['keterangan'] ?? null,
            'diverifikas_oleh' => $user->nama,
        ]);

        return Redirect::route('pegawai.pembayaran.index')->with('message', 'Pembayaran Berhasil Diupdate!');
    }
}
