<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\form_pengajuan;
use App\Models\pembayaran;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminPengajuanController extends Controller
{
        //lihat daftar pengajuan dari Admin
        public function index()
        {
            $pengajuan = form_pengajuan::with(['kategori', 'parameter', 'customer', 'jenis_cairan'])->get();
    
            return Inertia::render('admin/pengajuan/index', [
                'pengajuan' => $pengajuan
            ]);
        }
    
        //proses verifikasi pengajuan oleh admin
        public function verifikasi($id, Request $request)
        {
            $request->validate([
                'status_pengajuan' => 'required|in:diterima,ditolak'
            ]);
    
            $pengajuan = form_pengajuan::findOrFail($id);
    
            $pengajuan->status_pengajuan = $request->status_pengajuan;
    
            if($request->status_pengajuan == 'diterima')
            {
                $pengajuan->tanggal_terima == now();
    
                pembayaran::create([
                    'id' => $pengajuan->id,
                    'total_biaya' => $pengajuan->kategori->harga,
                    'status_pembayaran' => 'belum_dibayar'
                ]);
            }
    
            $pengajuan->save();
    
            return Redirect::route('pengajuan.index')->with('message', 'Pengajuan Telah Diterima!');
        }
}
