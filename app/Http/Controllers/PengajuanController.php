<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckVerifiedCustomer;
use App\Models\form_pengajuan;
use App\Models\kategori;
use App\Models\pembayaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PengajuanController extends Controller
{
    public function __construct()
    {
     $this->middleware(CheckVerifiedCustomer::class);   
    }

    //daftar pengajuan dari user
    public function register()
    {
        return Inertia::render('admin/pengajuan/daftar', [
            'kategoriList' => kategori::all()
        ]);
    }

    //proses daftar pengajuan dari user
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|exists:kategori,nama',
            'deskripsi' => 'required',
            'metode_pengambilan' => 'required|in:diantar,diambil'
        ]);

        $kategori = kategori::where('nama', $request->nama_kategori)->first();

        $pengajuan = form_pengajuan::create([
            'id_kategori' => $kategori->id,
            'deskripsi' => $request->deskripsi,
            'metode_pengambilan' => $request->metode_pengambilan,
            'status_pengajuan' => 'diproses'
        ]);

        return Redirect::route('pengajuan.show')->with('message', 'Pengajuan Berhasil Dibuat!');
    }

    //lihat pendaftaran yang didaftarkan dari user
    public function show()
    {
        // $customer = Auth::customer;
    }

    //lihat daftar pengajuan
    public function index()
    {
        $pengajuan = form_pengajuan::with('kategori')->latest()->get();

        return Inertia::render('admin/pengajuan/index', [
            'pengajuan' => $pengajuan
        ]);
    }

    //proses verifikasi pengajuan oleh admin
    public function verification($id, Request $request)
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
