<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\FormPengajuan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Notifications\PembayaranSukses;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['form_pengajuan', 'form_pengajuan.user'])->get();

        return Inertia::render('pegawai/pembayaran/Index', [
            'pembayaran' => $pembayaran
        ]);
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['form_pengajuan', 'form_pengajuan.user'])->findOrFail($id);

        return Inertia::render('pegawai/pembayaran/Detail', [
            'pembayaran' => $pembayaran
        ]);
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with(['form_pengajuan', 'form_pengajuan.user'])->findOrFail($id);

        return Inertia::render('pegawai/pembayaran/Edit', [
            'pembayaran' => $pembayaran
        ]);
    }

    public function update(Pembayaran $pembayaran, Request $request)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:diproses,selesai,gagal'
        ]);

        $pembayaran->update($request->all());

        if ($request->status_pembayaran === 'selesai' && $pembayaran->form_pengajuan && $pembayaran->form_pengajuan->user) {
            try {
                $pembayaran->form_pengajuan->user->notify(new PembayaranSukses($pembayaran));
            } catch (\Exception $err) {
                return Redirect::back()->withErrors([
                    'notification' => 'Gagal Mengirim Email:' . $err->getMessage()
                ]);
            }
        }

        return Redirect::route('pegawai.pembayaran.index')->with('message', 'Pembayaran Berhasil Diupdate!');
    }
}
