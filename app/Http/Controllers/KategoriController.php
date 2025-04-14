<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class KategoriController extends Controller
{

    //lihat kategori
    public function index()
    {
        $kategori = kategori::latest()->get();
        
        return Inertia::render('kategori/index', [
            'kategori' => $kategori
        ]);
    }

    //tambah kategori
    public function create()
    {
        return Inertia::render('kategori/create');    
    }

    //proses tambah kategori
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $kategori = kategori::create($request->all());

        if($kategori)
        {
            return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
        }
    }

    //edit kategori
    public function edit(kategori $kategori)
    {
        return Inertia::render('kategori/edit', [
            'kategori' => $kategori
        ]);
    }

    //proses update kategori
    public function update(kategori $kategori, Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $kategori = kategori::update($request->all());

        if($kategori)
        {
            return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Diupdate!');
        }
    }

    //proses delete kategori
    public function destroy($id)
    {
        $kategori = kategori::findOrFail($id);

        $kategori->delete();

        if($kategori)
        {
            return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Didelete!');
        }
    }
}
