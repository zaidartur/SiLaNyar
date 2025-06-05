<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FormPengajuan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $instansiUser = $user->instansi()->pluck('id')->toArray();

        if (empty($instansiUser)) {
            return Inertia::render('customer/dashboard/Index', [
                'statistik' => [
                    'proses' => 0,
                    'ditolak' => 0,
                    'diterima' => 0,
                ],
                'pengajuan' => [],
                'pilihPengajuan' => null,
                'statusList' => [],
                'pembayaran' => [],
                'auth' => [
                    'user' => $user,
                ],
                'error' => 'Tidak ada instansi yang tersedia',
            ]);
        }

        $proses = FormPengajuan::whereIn('id_instansi', $instansiUser)->where('status_pengajuan', 'proses_validasi')->count();
        $ditolak = FormPengajuan::whereIn('id_instansi', $instansiUser)->where('status_pengajuan', 'ditolak')->count();
        $diterima = FormPengajuan::whereIn('id_instansi', $instansiUser)->where('status_pengajuan', 'diterima')->count();

        $pengajuan = FormPengajuan::with(['jadwal', 'pembayaran', 'pengujian.hasil_uji', 'jenis_cairan', 'kategori'])
            ->whereIn('id_instansi', $instansiUser)
            ->orderByDesc('updated_at')
            ->get();

        $pilihPengajuan = null;
        if ($request->has('id')) {
            $pilihPengajuan = $pengajuan->firstWhere('id', $request->id);
            if ($pilihPengajuan && !in_array($pilihPengajuan->id_instansi, $instansiUser)) {
                $pilihPengajuan = null;
            }
        } else {
            $pilihPengajuan = $pengajuan->first() ?: null;
        }

        $statusList = [];

        if ($pilihPengajuan) {
            $pengujian = $pilihPengajuan->pengujian
                ->filter(function ($uji) {
                    return $uji->hasil_uji->contains('status', 'selesai');
                })
                ->last();
            $hasiluji = $pengujian?->hasil_uji
                ->firstWhere('status', 'selesai');
            $statusList = [
                [
                    'label' => 'Pengajuan Diterima',
                    'status' => $pilihPengajuan->status_pengajuan === 'diterima',
                    'tanggal' => $pilihPengajuan->status_pengajuan === 'diterima' ? $pilihPengajuan->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Pembayaran',
                    'status' => $pilihPengajuan->pembayaran && $pilihPengajuan->pembayaran->status === 'lunas',
                    'tanggal' => $pilihPengajuan->pembayaran && $pilihPengajuan->pembayaran->status === 'lunas' ? $pilihPengajuan->pembayaran->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Proses Pengantaran/pengambilan',
                    'status' => $pilihPengajuan->status_pengajuan === 'diterima' && $pilihPengajuan->jadwal && $pilihPengajuan->jadwal->status === 'diproses',
                    'tanggal' => $pilihPengajuan->jadwal && $pilihPengajuan->jadwal->status === 'diproses' ? $pilihPengajuan->jadwal->created_at->format('d-m-Y') : 'menunggu'
                ],
                [
                    'label' => 'Sampel Diterima Lab',
                    'status' => $pilihPengajuan->jadwal && $pilihPengajuan->jadwal->status === 'selesai',
                    'tanggal' => $pilihPengajuan->jadwal && $pilihPengajuan->jadwal->status === 'selesai' ? $pilihPengajuan->jadwal->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Pengujian Berjalan',
                    'status' => $pengujian && $pengujian->status === 'diproses',
                    'tanggal' => $pengujian && $pengujian->status === 'diproses' ? $pengujian->created_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Hasil Tersedia',
                    'status' => $hasiluji && $hasiluji->status === 'selesai',
                    'tanggal' => $hasiluji && $hasiluji->status === 'selesai' ? $hasiluji->updated_at->format('d-m-Y') : 'menunggu'
                ]
            ];
        } else {
            $statusList = [];
        }

        $pembayaran = $pengajuan->where('status_pengajuan', 'diterima')
            ->pluck('pembayaran')
            ->filter()
            ->values();

        return Inertia::render('customer/dashboard/Index', [
            'statistik' => [
                'proses' => $proses,
                'ditolak' => $ditolak,
                'diterima' => $diterima
            ],
            'pengajuan' => $pengajuan,
            'pilihPengajuan' => $pilihPengajuan,
            'statusList' => $statusList,
            'pembayaran' => $pembayaran,
            'auth' => [
                'user' => $user,
            ],
        ]);
    }
}
