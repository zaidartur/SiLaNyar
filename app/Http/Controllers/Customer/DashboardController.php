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

            $pengajuanDiterima = $pilihPengajuan->status_pengajuan === 'diterima';
            $pembayaranDiterima = $pengajuanDiterima && $pilihPengajuan->pembayaran && $pilihPengajuan->pembayaran->status === 'selesai';
            $jadwalDiproses = $pembayaranDiterima && $pilihPengajuan->jadwal && ($pilihPengajuan->jadwal->status === 'diproses' || $pilihPengajuan->jadwal->status === 'diterima');
            $sampelDiterima = $jadwalDiproses && $pilihPengajuan->jadwal && $pilihPengajuan->jadwal->status === 'diterima';
            $pengujianBerjalan = $sampelDiterima && $pengujian && ($pengujian->status === 'diproses' || $pengujian->status === 'selesai');
            $hasilUjiTersedia = $pengujianBerjalan && $hasiluji && ($hasiluji->status === 'selesai' || $hasiluji->status === 'proses_review' || $hasiluji->status === 'proses_peresmian');
            $statusList = [
                [
                    'label' => 'Pengajuan Diterima',
                    'status' => $pengajuanDiterima,
                    'tanggal' => $pengajuanDiterima ? $pilihPengajuan->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Pembayaran',
                    'status' => $pembayaranDiterima,
                    'tanggal' => $pembayaranDiterima ? $pilihPengajuan->pembayaran->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Jadwal Sampel',
                    'status' => $jadwalDiproses,
                    'tanggal' => $jadwalDiproses ? $pilihPengajuan->jadwal->created_at->format('d-m-Y') : 'menunggu'
                ],
                [
                    'label' => 'Sampel Diterima Lab',
                    'status' => $sampelDiterima,
                    'tanggal' => $sampelDiterima ? $pilihPengajuan->jadwal->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Pengujian Berjalan',
                    'status' => $pengujianBerjalan,
                    'tanggal' => $pengujianBerjalan ? $pengujian->created_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Hasil Tersedia',
                    'status' => $hasilUjiTersedia,
                    'tanggal' => $hasilUjiTersedia ? $hasiluji->updated_at->format('d-m-Y') : 'menunggu'
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
