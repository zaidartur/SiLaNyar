<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pengujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JadwalController extends Controller
{
    //lihat jadwal
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

        $jadwalAntarTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
            $query->where('metode_pengambilan', 'diantar')
                ->where('id_user', $user->id);
        })
            ->with('form_pengajuan')
            ->latest()
            ->first();

        $idJadwalAntarTerbaru = $jadwalAntarTerbaru?->id;

        $jadwalAmbilTerbaru = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
            $query->where('metode_pengambilan', 'diambil')
                ->where('id_user', $user->id);
        })
            ->with('form_pengajuan')
            ->latest()
            ->first();

        $idJadwalAmbilTerbaru = $jadwalAmbilTerbaru?->id;

        return Inertia::render('customer/jadwal/Index', [
            'jadwal' => $jadwal,
            'jadwalAntarTerbaru' => $idJadwalAntarTerbaru,
            'jadwalAmbilTerbaru' => $idJadwalAmbilTerbaru,
            'filter' => [
                'status' => $searchByStatus
            ],
        ]);
    }

    //detail jadwal
    public function show($id)
    {
        $user = Auth::user();

        $jadwal = Jadwal::whereHas('form_pengajuan', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })
            ->with(['form_pengajuan', 'form_pengajuan.kategori'])
            ->findOrFail($id)
            ->get();

        return Inertia::render('customer/jadwal/Detail', [
            'jadwal' => $jadwal
        ]);
    }
}
