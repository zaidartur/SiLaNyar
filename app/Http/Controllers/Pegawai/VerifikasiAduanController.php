<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Mail\AduanDiverifikasiNotification;
use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VerifikasiAduanController extends Controller
{
    public function index()
    {
        $aduan = Aduan::with(['user', 'hasil_uji'])->get();

        return Inertia::render('pegawai/aduan/Index', [
            'aduan' => $aduan
        ]);
    }

    public function show(Aduan $aduan)
    {
        $aduan->with(['user', 'hasil_uji'])->get();

        return Inertia::render('pegawai/aduan/Show', [
            'aduan' => $aduan
        ]);
    }

    public function verifikasi($id, Request $request)
    {
        $aduan = Aduan::with(['user', 'hasil_uji'])->findOrFail($id);

        $request->validate([
            'status' => 'required|in:diterima:ditolak'
        ]);

        $aduan->update($request->all());

        $user = $aduan->user->id;
        $user->notify(new AduanDiverifikasiNotification($aduan));

        return Redirect::route('pegawai/aduan/Index')->with('message', 'Aduan Berhasil Diverifikasi');
    }
}
