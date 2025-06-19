<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\HasilUji;
use App\Models\HasilUjiHistori;
use App\Models\ParameterUji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HasilUjiHistoriController extends Controller
{
    public function index($id)
    {
        $hasil_uji = HasilUji::findOrFail($id);

        $histori = HasilUjiHistori::with([
            'hasil_uji.pengujian.form_pengajuan.instansi.user',
            'hasil_uji.pengujian.user'
        ])
            ->where('id_hasil_uji', $id)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('pegawai/hasil_uji/Histori', [
            'hasil_uji' => $hasil_uji,
            'histori' => $histori
        ]);
    }

    public function show($id)
    {
        $histori = HasilUjiHistori::with([
            'hasil_uji.pengujian.form_pengajuan.kategori',
            'hasil_uji.pengujian.form_pengajuan.instansi.user',
            'hasil_uji.pengujian.user'
        ])->findOrFail($id);

        // Use the historical data stored in JSON column
        $data_parameter = $histori->data_parameterdanpengujian ?? [];

        return Inertia::render('pegawai/hasil_uji/ShowHistori', [
            'histori' => $histori,
            'data_parameter' => $data_parameter
        ]);
    }
}