<?php

namespace App\Http\Controllers\Customer;

use App\Models\FormPengajuan;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PengajuanController extends Controller
{
    //list pengajuan dari customer
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'jenis_cairan'])
            ->where('id_customer', $customer->id)
            ->get();

        return Inertia::render('customer/pengajuan/index', [
            'pengajuan' => $pengajuan
        ]);
    }

    //daftar pengajuan uji lab customer
    public function daftar()
    {
        $jenis_cairan = JenisCairan::all();
        $kategori = kategori::all();
        $parameter = ParameterUji::all();

        return Inertia::render('customer/pengajuan/tambah', [
            'kategori' => $kategori,
            'jenis_cairan' => $jenis_cairan,
            'parameter' => $parameter
        ]);
    }

    //proses daftar pengajuan uji lab customer
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'id_jenis_cairan' => 'required|exists:jenis_cairan,id',
            'volume_sampel' => 'required|float',
            'metode_pengambilan' => 'required|in:diantar,diambil',
            'lokasi' => 'nullable|string|max:255',
            'parameter' => 'required|array',
            'parameter.*' => 'exists:parameter_uji,id'
        ]);

        $jenis_cairan = JenisCairan::findOrFail($request->id_jenis_cairan);

        if ($request->volume_sampel < $jenis_cairan->batas_minimum || $request->volume_sampel > $jenis_cairan->batas_maksimum) {
            return Redirect::back()->withErrors([
                'volume_sampel' => "Volume Sampel Harus Diantara {$jenis_cairan->batas_minimum} atau {$jenis_cairan->batas_maksimum} Untuk Jenis Cairan {$jenis_cairan->nama}"
            ])->withInput();
        }

        $pengajuan = FormPengajuan::create([
            'id_customer' => Auth::guard('customer')->id(),
            'id_kategori' => $request->id_kategori,
            'id_jenis_cairan' => $request->id_jenis_cairan,
            'volume_sampel' => $request->volume_sampel,
            'metode_pengambilan' => $request->metode_pengambilan,
            'lokasi' => $request->lokasi,
        ]);

        if ($pengajuan) {
            return Redirect::route('customer.pengajuan.index')->with('message', 'Pengajuan Berhasil Ditambahkan, Harap Tunggu Verifikasi Administrasi Dalam Waktu 1x2 Minggu');
        }
    }

    //lihat detail pengajuan dari user
    public function show($id)
    {
        $customer = Auth::guard('customer')->user();

        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'jenis_cairan'])
            ->where('id', $id)
            ->where('id_customer', $customer->id)
            ->firstOrFail();

        return Inertia::render('customer/pengajuan/detail', [
            'pengajuan' => $pengajuan
        ]);
    }
}