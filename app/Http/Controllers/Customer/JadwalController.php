<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $searchByStatus = $request->input('status');

        $instansiUser = $user->instansi()->pluck('id')->toArray();

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
        $jadwalAntarTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($instansiUser) {
            $query->where('metode_pengambilan', 'diantar')
                ->whereIn('id_instansi', $instansiUser);
        })
            ->with('form_pengajuan')
            ->latest()
            ->first();

        $idJadwalAntarTerbaru = $jadwalAntarTerbaru?->id;

        // Jadwal ambil terbaru
        $jadwalAmbilTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($instansiUser) {
            $query->where('metode_pengambilan', 'diambil')
                ->whereIn('id_instansi', $instansiUser);
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

    public function pengantaran(Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $instansiUser = $user->instansi()->pluck('id')->toArray();

        $jadwalAntarTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($instansiUser) {
            $query->where('metode_pengambilan', 'diantar')->whereIn('id_instansi', $instansiUser);
        })
            ->with('form_pengajuan')
            ->get();

        return Inertia::render('customer/jadwal/Pengantaran', [
            'jadwalAntarTerbaru' => $jadwalAntarTerbaru,
        ]);
    }

    public function penjemputan(Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $instansiUser = $user->instansi()->pluck('id')->toArray();

        $jadwalAmbilTerbaru = Jadwal::whereHas('form_pengajuan', function ($query)  use ($instansiUser) {
            $query->where('metode_pengambilan', 'diambil')->whereIn('id_instansi', $instansiUser);
        })
            ->with('form_pengajuan')
            ->get();

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
