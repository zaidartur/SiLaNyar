<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\form_pengajuan;
use App\Models\pembayaran;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index($id)
    {
        $pengajuan = form_pengajuan::with(['jadwal', 'pembayaran', 'pengujian', 'hasil_uji'])
                    ->findOrFail($id);
        
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

        return Inertia::render('dashboard/customer', [
            'statusList' => $statusList,
            'pengajuan' => $pengajuan,
        ]);
    }
}
