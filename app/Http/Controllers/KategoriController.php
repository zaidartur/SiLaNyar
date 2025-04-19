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
        
        return Inertia::render('kategori/index', [
            'kategori' => $kategori,
        ]);
    }

    //form tambah kategori
    public function create()
    {
        $parameter = parameter_uji::all();

        return Inertia::render('kategori/create', [
            'parameter' => $parameter
        ]);    
    }

    //proses tambah kategori
    public function store(Request $request)
    {
        $request->validate([
            'id_parameter' => 'required|exists:parameter_uji,id',
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0'
        ]);

        $kategori = kategori::create($request->all());

        if(request()->header('referer') && str_contains(request()->header('referer'), '/test/')) {
            return Redirect::route('test.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
        }
        
        if($kategori)
        {
            return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
        }
    }

    //form edit kategori
    public function edit(kategori $kategori)
    {
        $parameter = parameter_uji::all();
        
        return Inertia::render('kategori/edit', [
            'kategori' => $kategori,
            'parameter' => $parameter,
        ]);
    }

    //proses update kategori
    public function update(kategori $kategori, Request $request)
    {
        $request->validate([
            'id_parameter' => 'required|exists:parameter_uji,id',
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0'
        ]);

        $kategori = kategori::update($request->all());

        if(request()->header('referer') && str_contains(request()->header('referer'), '/test/')) {
            return Redirect::route('test.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
        }

        if($kategori)
        {
            return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Diupdate!');
        }
    }

    //lihat detail kategori
    public function show(kategori $kategori)
    {
        $kategori->load('parameter');
        
        return Inertia::render('kategori/show', [
            'kategori' => $kategori
        ]);
    }

    //proses delete kategori
    public function destroy($id)
    {
        $kategori = kategori::findOrFail($id);

        $kategori->delete();

        if(request()->header('referer') && str_contains(request()->header('referer'), '/test/')) {
            return Redirect::route('test.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
        }
        
        if($kategori)
        {
            return Redirect::route('kategori.index')->with('message', 'Kategori Berhasil Didelete!');
        }
    }
}
