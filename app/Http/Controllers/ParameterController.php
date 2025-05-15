<?php

namespace App\Http\Controllers;

use App\Models\ParameterUji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ParameterController extends Controller
{
    //lihat daftar parameter
    public function index()
    {
        $parameter = ParameterUji::latest()->get();
        
        return Inertia::render('pegawai/parameter/Index', [
            'parameter' => $parameter,
            'filter' => request()->all()
        ]);
    }

    //form tambah parameter
    public function create()
    {
        return Inertia::render('pegawai/parameter/Tambah');
    }

    //proses tambah parameter
    public function store(Request $request)
    {
        $request->validate([
            'nama_parameter'=>'required|string',
            'satuan'=>'required|string',
            'harga'=>'required|numeric|min:0',
        ]);

        $parameter = ParameterUji::create($request->all());

        if($parameter)
        {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Dibuat!');
        }
    }

    //form edit parameter
    public function edit(ParameterUji $parameter)
    {
        return Inertia::render('pegawai/parameter/Edit', [
            'parameter' => $parameter
        ]);
    }

    //proses update parameter
    public function update(ParameterUji $parameter, Request $request)
    {
        $request->validate([
            'nama_parameter'=>'required|string',
            'satuan'=>'required|string',
            'harga'=>'required|numeric|min:0',
        ]);

        $parameter->update($request->all());

        if($parameter)
        {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Diupdate!');
        }
    }

    //proses hapus parameter
    public function destroy($id)
    {
        $parameter = ParameterUji::findOrFail($id);
        
        $parameter->delete();

        if($parameter)
        {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Dihapus!');
        }
    }
}
