<?php

namespace App\Http\Controllers;

use App\Models\parameter_uji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ParameterController extends Controller
{
    //lihat daftar parameter
    public function index()
    {
        $parameter = parameter_uji::latest()->get();
        
        return Inertia::render('pegawai/parameter/index', [
            'parameter' => $parameter
        ]);
    }

    //form tambah parameter
    public function create()
    {
        return Inertia::render('pegawai/parameter/tambah');
    }

    //proses tambah parameter
    public function store(Request $request)
    {
        $request->validate([
            'nama_parameter'=>'required|string',
            'satuan'=>'required|string',
            'baku_mutu'=>'required',
            'biaya'=>'required|numeric|min:0',
        ]);

        $parameter = parameter_uji::create($request->all());

        if($parameter)
        {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Dibuat!');
        }
    }

    //form edit parameter
    public function edit(parameter_uji $parameter)
    {
        return Inertia::render('pegawai/parameter/edit', [
            'parameter' => $parameter
        ]);
    }

    //proses update parameter
    public function update(parameter_uji $parameter, Request $request)
    {
        $request->validate([
            'nama_parameter'=>'required|string',
            'satuan'=>'required|string',
            'baku_mutu'=>'required',
            'biaya'=>'required|numeric|min:0',
        ]);

        $parameter = parameter_uji::update($request->all());

        if($parameter)
        {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Diupdate!');
        }
    }

    //lihat detail parameter
    public function show(parameter_uji $parameter)
    {
        return Inertia::render('pegawai/parameter/detail', [
            'parameter' => $parameter
        ]);
    }

    //proses hapus parameter
    public function destroy($id)
    {
        $parameter = parameter_uji::findOrFail($id);
        
        $parameter->delete();

        if($parameter)
        {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Dihapus!');
        }
    }
}
