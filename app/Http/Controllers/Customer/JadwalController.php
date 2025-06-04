<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JadwalController extends Controller
{
    // Lihat semua jadwal (bisa difilter status)
    public function index(Request $request)
    {
        $searchByStatus = $request->input('status');
        $user = Auth::user();

        $jadwal = Jadwal::whereHas('form_pengajuan', function ($query) use ($user, $searchByStatus) {
                $query->where('id_user', $user->id);
                if ($searchByStatus) {
                    $query->where('status', 'like', '%' . $searchByStatus . '%');
                }
            })
            ->orderBy('waktu_pengambilan')
            ->with('form_pengajuan')
            ->get();

        // Jadwal antar terbaru
        $jadwalAntarTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
                $query->where('metode_pengambilan', 'diantar')
                    ->where('id_user', $user->id);
            })
            ->with('form_pengajuan')
            ->latest()
            ->first();

        $idJadwalAntarTerbaru = $jadwalAntarTerbaru?->id;

        // Jadwal ambil terbaru
        $jadwalAmbilTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
                $query->where('metode_pengambilan', 'diambil')
                    ->where('id_user', $user->id);
            })
            ->with('form_pengajuan')
            ->latest()
            ->first();

        $idJadwalAmbilTerbaru = $jadwalAmbilTerbaru?->id;

        return Inertia::render('customer/jadwal/Pengantaran', [
            'jadwal' => $jadwal,
            'jadwalAntarTerbaru' => $idJadwalAntarTerbaru,
            'jadwalAmbilTerbaru' => $idJadwalAmbilTerbaru,
            'filter' => [
                'status' => $searchByStatus
            ],
        ]);
    }

    // Halaman khusus pengantaran
    public function pengantaran(Request $request)
    {
        $user = Auth::user();

        $jadwalAntarTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
                $query->where('metode_pengambilan', 'diantar')
                    ->where('id_user', $user->id);
            })
            ->with('form_pengajuan')
            ->latest()
            ->first();

        return Inertia::render('customer/jadwal/Pengantaran', [
            'jadwalAntarTerbaru' => $jadwalAntarTerbaru,
        ]);
    }

    // Halaman khusus penjemputan
    public function penjemputan(Request $request)
    {
        $user = Auth::user();

        $jadwalAmbilTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
                $query->where('metode_pengambilan', 'diambil')
                    ->where('id_user', $user->id);
            })
            ->with('form_pengajuan')
            ->latest()
            ->first();

        return Inertia::render('customer/jadwal/Penjemputan', [
            'jadwalAmbilTerbaru' => $jadwalAmbilTerbaru,
        ]);
    }

    // Detail jadwal
    public function show($id)
    {
        $user = Auth::user();

        $jadwal = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            })
            ->with(['form_pengajuan', 'form_pengajuan.kategori'])
            ->findOrFail($id);

        return Inertia::render('customer/jadwal/Detail', [
            'jadwal' => $jadwal
        ]);
    }
}