<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use App\Models\parameter_uji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class KategoriController extends Controller
{

    //lihat daftar kategori
    public function index()
    {
        $kategori = kategori::with('parameter')->get();
        
        return Inertia::render('pegawai/kategori/index', [
            'kategori' => $kategori,
        ]);
    }

    //form tambah kategori
    public function create()
    {
        $parameter = parameter_uji::all();

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

<<<<<<< HEAD
        $kategori = kategori::create($request->only([
            'nama',
            'harga'
        ]));

        // Attach parameters
        if ($request->parameter_ids) {
            $kategori->parameter()->attach($request->parameter_ids);
        }

        if(request()->header('referer') && str_contains(request()->header('referer'), '/test/')) {
            return Redirect::route('test.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
        }
        
        return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
=======
        $kategori = kategori::create($request->all());
        
        if($kategori)
        {
            return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
        }
>>>>>>> 602f626 (Update dan Revisi Inertia::render, Name)
    }

    //form edit kategori
    public function edit(kategori $kategori)
    {
        $parameter = parameter_uji::all();
        
        return Inertia::render('pegawai/kategori/edit', [
            'kategori' => $kategori,
            'parameter' => $parameter,
        ]);
    }

    //proses update kategori
    public function update(kategori $kategori, Request $request)
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

        // Update parameter relationships
        $kategori->parameter()->sync($request->parameter_ids);

<<<<<<< HEAD
        if(request()->header('referer') && str_contains(request()->header('referer'), '/test/')) {
            return Redirect::route('test.kategori.index')->with('message', 'Kategori Berhasil Diupdate!');
        }

        return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Diupdate!');
=======
        if($kategori)
        {
            return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Diupdate!');
        }
>>>>>>> 602f626 (Update dan Revisi Inertia::render, Name)
    }

    //lihat detail kategori
    public function show(kategori $kategori)
    {
        $kategori->load('parameter');
        
        return Inertia::render('pegawai/kategori/detail', [
            'kategori' => $kategori
        ]);
    }

    //proses delete kategori
    public function destroy($id)
    {
        $kategori = kategori::findOrFail($id);

        $kategori->delete();
        
        if($kategori)
        {
            return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Didelete!');
        }
    }
}
