<?php

namespace App\Http\Controllers;

use App\Models\JenisCairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JenisCairanController extends Controller
{
    public function index()
    {
        $jenis_cairan = JenisCairan::all();
        return Inertia::render('pegawai/jenis_cairan/Index', [
            'jenisCairan' => $jenis_cairan
        ]);    
    }

    public function create()
    {
        return Inertia::render('pegawai/jenis_cairan/Tambah');    
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'batas_minimum' => 'required|numeric',
            'batas_maksimum' => 'required|numeric'
        ]);

        $jenis_cairan = JenisCairan::create($request->all());

        if($jenis_cairan)
        {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Ditambahkan');
        }
    }

    public function edit(JenisCairan $jenis_cairan)
    {
        return Inertia::render('pegawai/jenis_cairan/Edit',[
            'jenis_cairan' => $jenis_cairan
        ]);
    }

    public function update(JenisCairan $jenis_cairan, Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'batas_minimum' => 'required|numeric',
            'batas_maksimum' => 'required|numeric'
        ]);

        $jenis_cairan->update($request->all());

        if($jenis_cairan)
        {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Diedit!');
        }
    }

    public function destroy($id)
    {
        $jenis_cairan = JenisCairan::findOrFail($id);
        
        $jenis_cairan->delete();

        if($jenis_cairan)
        {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Dihapus!');
        }
    }
}
