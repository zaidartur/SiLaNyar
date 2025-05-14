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

        return Inertia::render('customer/pengajuan/Index', [
            'pengajuan' => $pengajuan
        ]);
    }

    //daftar pengajuan uji lab customer
    public function daftar()
    {
        $jenis_cairan = JenisCairan::all();
        $kategori = kategori::all();
        $parameter = ParameterUji::all();

        return Inertia::render('customer/pengajuan/Tambah', [
            'kategori' => $kategori,
            'jenis_cairan' => $jenis_cairan,
            'parameter' => $parameter
        ]);
    }

    //proses daftar pengajuan uji lab customer
    public function store(Request $request)
    {
        $jenisCairan = JenisCairan::findOrFail($request->id_jenis_cairan);

        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'id_jenis_cairan' => 'required|exists:jenis_cairan,id',
            'volume_sampel' => [
                'required',
                'numeric',
                'min:' . $jenisCairan->batas_minimum,
                'max:' . $jenisCairan->batas_maksimum
            ],
            'metode_pengambilan' => 'required|in:diantar,diambil',
            'lokasi' => 'required_if:metode_pengambilan,diambil|nullable|string|max:255',
            'parameter' => 'required|array',
            'parameter.*' => 'exists:parameter_uji,id'
        ], [
            'volume_sampel.min' => "Volume Sampel Harus Diantara {$jenisCairan->batas_minimum} atau {$jenisCairan->batas_maksimum} Untuk Jenis Cairan",
            'volume_sampel.max' => "Volume Sampel Harus Diantara {$jenisCairan->batas_minimum} atau {$jenisCairan->batas_maksimum} Untuk Jenis Cairan"
        ]);

        $pengajuan = FormPengajuan::create([
            'id_customer' => Auth::guard('customer')->id(),
            'id_kategori' => $validated['id_kategori'],
            'id_jenis_cairan' => $validated['id_jenis_cairan'],
            'volume_sampel' => $validated['volume_sampel'],
            'metode_pengambilan' => $validated['metode_pengambilan'],
            'lokasi' => $validated['lokasi'],
        ]);

        if ($pengajuan) {
            return redirect()->route('customer.pengajuan.index')
                ->with('message', 'Pengajuan Berhasil Ditambahkan');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal membuat pengajuan']);
    }

    //lihat detail pengajuan dari user
    public function show($id)
    {
        $customer = Auth::guard('customer')->user();

        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'jenis_cairan'])
            ->where('id', $id)
            ->where('id_customer', $customer->id)
            ->firstOrFail();

        return Inertia::render('customer/pengajuan/Detail', [
            'pengajuan' => $pengajuan
        ]);
    }
}