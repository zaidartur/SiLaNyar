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
        
        return Inertia::render('parameter/index', [
            'parameter' => $parameter
        ]);
    }

    //form tambah parameter
    public function create()
    {
        return Inertia::render('parameter/create');
    }

    //proses tambah parameter
    public function store(Request $request)
    {
        $request->validate([
            'nama_parameter'=>'required|string',
            'satuan'=>'required|string',
            'baku_mutu'=>'required',
            'harga'=>'required|numeric|min:0',
        ]);

        $parameter = parameter_uji::create($request->all());

        if($parameter)
        {
            return Redirect::route('parameter.index')->with('message', 'Parameter Berhasil Dibuat!');
        }
    }

    //form edit parameter
    public function edit(parameter_uji $parameter)
    {
        return Inertia::render('parameter/edit', [
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
            'harga'=>'required|numeric|min:0',
        ]);

        // Perbaikan: Gunakan instance $parameter yang sudah di-inject
        $parameter->update($request->all());

        if($parameter)
        {
            return Redirect::route('parameter.index')->with('message', 'Parameter Berhasil Diupdate!');
        }
    }

    //proses hapus parameter
    public function destroy($id)
    {
        $parameter = parameter_uji::findOrFail($id);
        
        $parameter->delete();

        if($parameter)
        {
            return Redirect::route('parameter.index')->with('message', 'Parameter Berhasil Dihapus!');
        }
    }
}
