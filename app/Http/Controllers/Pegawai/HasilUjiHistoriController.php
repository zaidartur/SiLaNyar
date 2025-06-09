<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\HasilUji;
use App\Models\HasilUjiHistori;
use App\Models\ParameterUji;
use Illuminate\Http\Request;
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
            'hasil_uji.pengujian.form_pengajuan.kategori.parameter',
            'hasil_uji.pengujian.form_pengajuan.kategori.subkategori.parameter',
            'hasil_uji.pengujian.form_pengajuan.instansi.user',
            'hasil_uji.pengujian.user'
        ])->findOrFail($id);

        $kategori = $histori->hasil_uji->pengujian->form_pengajuan->kategori;

        $parameterKategori = $kategori->parameter->map(function ($param) {
            return [
                'id' => $param->id,
                'nama' => $param->nama_parameter,
                'satuan' => $param->satuan,
                'baku_mutu' => $param->pivot->baku_mutu ?? null,
            ];
        });

        $parameterSubKategori = $kategori->subkategori->flatMap(function ($sub) {
            return $sub->parameter->map(function ($param) {
                return [
                    'id' => $param->id,
                    'nama' => $param->nama_parameter,
                    'satuan' => $param->satuan,
                    'baku_mutu' => $param->pivot->baku_mutu ?? null,
                ];
            });
        });

        $semuaParameter = $parameterKategori->merge($parameterSubKategori)->keyBy('id');

        // Ubah untuk membaca dari data_parameterdanpengujian.parameter
        $parameterData = $histori->data_parameterdanpengujian['parameter'] ?? [];
        $idParameter = collect($parameterData)->pluck('id_parameter')->unique();

        $parameterMap = ParameterUji::whereIn('id', $idParameter)
            ->get()
            ->keyBy('id')
            ->map(fn($item) => $item->nama_parameter);

        $dataParameter = collect($parameterData)->map(function ($item) use ($parameterMap, $semuaParameter) {
            $idParameter = $item['id_parameter'];

            return [
                'nama_parameter' => $parameterMap[$idParameter] ?? 'Tidak Ditemukan',
                'nilai' => $item['nilai'] ?? null,
                'baku_mutu' => $semuaParameter[$idParameter]['baku_mutu'] ?? 'Tidak Ditemukan',
                'keterangan' => $item['keterangan'] ?? null,
            ];
        });
        
        return Inertia::render('pegawai/hasil_uji/ShowHistori', [
            'histori' => $histori,
            'data_parameter' => $dataParameter
        ]);
    }
}
