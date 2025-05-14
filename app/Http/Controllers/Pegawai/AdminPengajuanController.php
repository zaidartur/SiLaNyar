<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormPengajuan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminPengajuanController extends Controller
{
        //lihat daftar pengajuan dari Admin
        public function index()
        {
            $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'customer', 'jenis_cairan'])
                        ->orderByDesc('updated_at')
                        ->get();
    
            return Inertia::render('pegawai/pengajuan/Index', [
                'pengajuan' => $pengajuan,
                'filter' => request()->all()
            ]);
        }
    
        //lihat detail pengajuan dari Admin
        public function show($id)
        {
            $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'customer', 'jenis_cairan'])
                        ->where('id', $id)
                        ->firstOrFail();

            return Inertia::render('pegawai/pengajuan/Detail', [
                'pengajuan' => $pengajuan
            ]);
        }

        //edit pengajuan dari admin
        public function edit(FormPengajuan $pengajuan)
        {
            return Inertia::render('pegawai/pengajuan/Edit', [
                'pengajuan' => $pengajuan
            ]);
        }
        
        //proses verifikasi pengajuan oleh admin
        public function update($id, Request $request)
        {
            $request->validate([
                'status_pengajuan' => 'required|in:diterima,ditolak'
            ]);
    
            $pengajuan = FormPengajuan::findOrFail($id);
            $pengajuan->status_pengajuan = $request->status_pengajuan;
    
            if ($request->status_pengajuan == 'diterima') {
                // Generate ID Order
                $idOrder = 'LAB-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
                Pembayaran::create([
                    'id_order' => $idOrder,
                    'id_form_pengajuan' => $pengajuan->id,
                    'total_biaya' => $pengajuan->kategori->harga,
                    'status_pembayaran' => 'belum_dibayar'
                ]);
            }
    
            $pengajuan->save();
    
            return redirect()->route('pegawai.pengajuan.index')
                ->with('message', 'Pengajuan Telah ' . ucfirst($request->status_pengajuan) . '!');
        }
}