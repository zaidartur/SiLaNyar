<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VerifikasiInstansiController extends Controller
{
    //daftar instansi
    public function index()
    {
        $instansi = Instansi::with(['user'])->get();

        return Inertia::render('pegawai/instansi/Index', [
            'instansi' => $instansi
        ]);
    }

    //edit instansi
    public function edit(Instansi $instansi)
    {
        $instansi->with(['user'])->get();

        return Inertia::render('pegawai/instansi/Edit', [
            'instansi' => $instansi
        ]);
    }

    //proses verifikasi instansi
    public function verifikasi($id, Request $request)
    {
        $instansi = Instansi::findOrFail($id);

        if ($instansi->status_verifikasi !== 'diproses') {
            return Redirect::back()->withErrors([
                'status_verifikasi' => 'Status Sudah Diverifikasi dan Tidak Bisa Diubah Lagi'
            ]);
        }

        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak'
        ]);

        $instansi->update([
            'status_verifikasi' => $request->status_verifikasi,
            'diverifikasi_oleh' => auth()->guard('pegawai')->id()
        ]);

        return Redirect::route('pegawai.instansi.index')->with('message', 'Verifikasi Berhasil Diupdate');
    }

    //detail instansi
    public function show(Instansi $instansi)
    {
        $instansi->with(['user'])->get();

        return Inertia::render('pegawai/instansi/Detail', [
            'instansi' => $instansi
        ]);
    }
}
