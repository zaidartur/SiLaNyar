<?php

namespace App\Http\Controllers;

use App\Models\jenis_cairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JenisCairanController extends Controller
{
    public function index()
    {
        return Inertia::render('pegawai/jenis_cairan/index');    
    }

    public function create()
    {
        return Inertia::render('pegawai/jenis_cairan/tambah');    
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'batas_minimum' => 'required|float',
            'batas_maksimum' => 'required|float'
        ]);

        $jenis_cairan = jenis_cairan::create($request->all());

        if($jenis_cairan)
        {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Ditambahkan');
        }
    }

    public function edit(jenis_cairan $jenis_cairan)
    {
        return Inertia::render('pegawai/jenis_cairan/edit',[
            'jenis_cairan' => $jenis_cairan
        ]);
    }

    public function update(jenis_cairan $jenis_cairan, Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'batas_minimum' => 'required|float',
            'batas_maksimum' => 'required|float'
        ]);

        $jenis_cairan->update($request->all());

        if($jenis_cairan)
        {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Diedit!');
        }
    }

    public function destroy($id)
    {
        $jenis_cairan = jenis_cairan::findOrFail($id);
        
        $jenis_cairan->delete();

        if($jenis_cairan)
        {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Dihapus!');
        }
    }
}
