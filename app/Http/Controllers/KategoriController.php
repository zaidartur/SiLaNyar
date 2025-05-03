<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\ParameterUji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class KategoriController extends Controller
{

    //lihat daftar kategori
    public function index()
    {
        $kategori = Kategori::with('parameter')->get();
        
        return Inertia::render('pegawai/kategori/index', [
            'kategori' => $kategori,
        ]);
    }

    //form tambah kategori
    public function create()
    {
        $parameter = ParameterUji::all();

        return Inertia::render('pegawai/kategori/tambah', [
            'parameter' => $parameter
        ]);    
    }

    //proses tambah kategori
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kategori,nama',
            'harga' => 'required|numeric|min:0',
            'parameter_ids' => 'required|array',
            'parameter_ids.*' => 'exists:parameter_uji,id'
        ]);

        $kategori = Kategori::create($request->only([
            'nama',
            'harga'
        ]));

        if ($request->parameter_ids) {
            $kategori->parameter()->attach($request->parameter_ids);
        }
        
        return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
    }

    //form edit kategori
    public function edit(Kategori $kategori)
    {
        $parameter = ParameterUji::all();
        
        return Inertia::render('pegawai/kategori/edit', [
            'kategori' => $kategori,
            'parameter' => $parameter,
        ]);
    }

    //proses update kategori
    public function update(Kategori $kategori, Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'parameter_ids' => 'required|array'
        ]);

        $kategori->update($request->only([
            'nama',
            'harga'
        ]));

        $kategori->parameter()->sync($request->parameter_ids);

        return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Diupdate!');
    }

    //proses delete kategori
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->delete();
        
        if($kategori)
        {
            return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Didelete!');
        }
    }
}
