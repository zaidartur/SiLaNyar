<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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

        $pengujian = Pengujian::whereHas('form_pengajuan', function ($query) use ($user, $searchByStatus) {
            $query->where('id_user', $user->id);

            if ($searchByStatus) {
                $query->where('status', 'like', '%' . $searchByStatus . '%');
            }
        })
            ->orderBy('tanggal_uji')
            ->with('form_pengajuan')
            ->get();

        return Inertia::render('customer/jadwal/Index', [
            'pengujian' => $pengujian,
            'filter' => [
                'status' => $searchByStatus
            ],
        ]);
    }

    //detail jadwal
    public function show($id)
    {
        $user = Auth::user();

        $pengujian = Pengujian::with('pegawai', 'kategori')
            ->whereHas('form_pengajuan', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            })
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('customer/jadwal/Detail', [
            'pengujian' => $pengujian
        ]);
    }
}
