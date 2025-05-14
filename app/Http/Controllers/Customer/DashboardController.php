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
        $customer = Auth::guard('customer')->user();

        $proses = FormPengajuan::where('id_customer', $customer)->where('status_pengajuan', 'proses_validasi')->count();
        $ditolak = FormPengajuan::where('id_customer', $customer)->where('status_pengajuan', 'ditolak')->count();
        $diterima = FormPengajuan::where('id_customer', $customer)->where('status_pengajuan', 'diterima')->count();

        $pengajuan = FormPengajuan::with(['jadwal', 'pembayaran', 'pengujian', 'hasil_uji'])
            ->where('id_customer', $customer)
            ->orderByDesc('updated_at')
            ->get();

        $pilihPengajuan = null;
        if ($request->has('id')) {
            $pilihPengajuan = $pengajuan->firstWhere('id', $request->id);
        } else {
            $pilihPengajuan = $pengajuan->first();
        }

        $statusList = [];

        if ($pilihPengajuan) {
            $statusList = [
                [
                    'label' => 'Pengajuan Diterima',
                    'status' => $pengajuan->status_pengajuan === 'diterima',
                    'tanggal' => $pengajuan->status_pengajuan === 'diterima' ? $pengajuan->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Pembayaran',
                    'status' => $pengajuan->pembayaran && $pengajuan->pembayaran->status === 'lunas',
                    'tanggal' => $pengajuan->pembayaran && $pengajuan->pembayaran->status === 'lunas' ? $pengajuan->pembayaran->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Proses Pengantaran/pengambilan',
                    'status' => $pengajuan->status_pengajuan === 'diterima' && $pengajuan->jadwal && $pengajuan->jadwal->status === 'diproses',
                    'tanggal' => $pengajuan->jadwal && $pengajuan->jadwal->status === 'diproses' ? $pengajuan->jadwal->created_at->format('d-m-Y') : 'menunggu'
                ],
                [
                    'label' => 'Sampel Diterima Lab',
                    'status' => $pengajuan->jadwal && $pengajuan->jadwal->status === 'selesai',
                    'tanggal' => $pengajuan->jadwal && $pengajuan->jadwal->status === 'selesai' ? $pengajuan->jadwal->updated_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Pengujian Berjalan',
                    'status' => $pengajuan->pengujian && $pengajuan->pengujian->status === 'diproses',
                    'tanggal' => $pengajuan->pengujian && $pengajuan->pengujian->status === 'diproses' ? $pengajuan->pengujian->created_at->format('d-m-Y') : 'menunggu',
                ],
                [
                    'label' => 'Hasil Tersedia',
                    'status' => $pengajuan->hasil_uji && $pengajuan->hasil_uji->status === 'selesai',
                    'tanggal' => $pengajuan->hasil_uji && $pengajuan->hasil_uji->status === 'selesai' ? $pengajuan->hasil_uji->updated_at->format('d-m-Y') : 'menunggu'
                ]
            ];
        }


        return Inertia::render('Dashboard', [
            'statistik' => [
                'proses' => $proses,
                'ditolak' => $ditolak,
                'diterima' => $diterima
            ],
            'pengajuan' => $pengajuan,
            'pilihPengajuan' => $pilihPengajuan,
            'statusList' => $statusList
        ]);
    }
}
