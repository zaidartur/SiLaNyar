<?php

namespace App\Http\Controllers;

use App\Models\hasil_uji;
use App\Models\parameter_uji;
use App\Models\pengujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HasilUjiController extends Controller
{
    //lihat list hasil uji
    public function index()
    {
        $hasil_uji = hasil_uji::with('parameter', 'pengujian', 'kategori');

        return Inertia::render('pegawai/hasil_uji/index', [
            'hasil_uji' => $hasil_uji
        ]);
    }


    //form tambah hasil uji
    public function create()
    {
        $parameter = parameter_uji::all();
        $pengujian = pengujian::all();
        
        return Inertia::render('pegawai/hasil_uji/tambah', [
            'parameter' => $parameter,
            'pengujian' => $pengujian,
        ]);
    }

    //proses tambah hasil uji
    public function store(Request $request)
    {
        $request->validate([
            'id_parameter' => 'required|exists:parameter_uji,id',
            'id_pengujian' => 'required|exists:pengujian,id',
            'nilai' => 'required|numeric',
            'keterangan' => 'required|string|max:255'
        ]);

        $hasil_uji = hasil_uji::create($request->all());

        if($hasil_uji)
        {
            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Dibuat!');
        }
    }

    //form edit hasil uji
    public function edit(hasil_uji $hasil_uji)
    {
        $parameter = parameter_uji::all();
        $pengujian = pengujian::all();
        
        return Inertia::render('pegawai/hasil_uji/edit', [
            'hasil_uji' => $hasil_uji,
            'parameter' => $parameter,
            'pengujian' => $pengujian
        ]);
    }

    //proses update hasil uji
    public function update(hasil_uji $hasil_uji, Request $request)
    {
        $request->validate([
            'id_parameter' => 'required|exists:parameter_uji,id',
            'id_pengujian' => 'required|exists:pengujian,id',
            'nilai' => 'required|numeric',
            'keterangan' => 'required|string|max:255'
        ]);
        
        $hasil_uji = hasil_uji::update($request->all());

        if ($hasil_uji)
        {
            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Diupdate!');
        }
    }

    //lihat detail hasil uji
    public function show(hasil_uji $hasil_uji)
    {
        $hasil_uji->load('parameter', 'pengujian');

        return Inertia::render('pegawai/hasil_uji/detail', [
            'hasil_uji' => $hasil_uji
        ]);
    }

    //hapus hasil uji
    public function destroy($id)
    {
        $hasil_uji = hasil_uji::findOrFail($id);
        
        $hasil_uji->delete();

        if($hasil_uji)
        {
            return Redirect::route('pegawai.hasil_uji.index')->with('message','Hasil Uji Berhasil Dihapus!');
        }
    }
}
