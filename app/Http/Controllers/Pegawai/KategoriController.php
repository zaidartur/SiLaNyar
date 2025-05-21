<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
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
        $kategori = Kategori::load(['parameter' => function ($baku_mutu) {
            $baku_mutu->withPivot('baku_mutu');
        }])->get();

        return Inertia::render('pegawai/kategori/Index', [
            'kategori' => $kategori,
        ]);
    }

    //form tambah kategori
    public function create()
    {
        $parameter = ParameterUji::all();

        return Inertia::render('pegawai/kategori/Tambah', [
            'parameter' => $parameter
        ]);
    }

    //proses tambah kategori
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kategori,nama',
            'harga' => 'required|numeric|min:0',
            'parameter' => 'required|array',
            'parameter.*.id' => 'required|exists:parameter_uji,id',
            'parameter.*.baku_mutu' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create($request->only([
            'nama',
            'harga',
        ]));

        $syncData = [];
        foreach ($request->parameter as $param) {
            $syncData[$param['id']] = ['baku_mutu' => $param['baku_mutu']];
        }

        $kategori->parameter()->attach($syncData);

        return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
    }

    //form edit kategori
    public function edit(Kategori $kategori)
    {
        $kategori->load(['parameter' => function ($baku_mutu) {
            $baku_mutu->withPivot('baku_mutu');
        }]);

        $parameter = ParameterUji::all();

        return Inertia::render('pegawai/kategori/Edit', [
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
            'parameter' => 'required|array',
            'parameter.*.id' => 'required|exists:parameter_uji,id',
            'parameter.*.baku_mutu' => 'required|string|max:255'
        ]);

        $kategori->update($request->only([
            'nama',
            'harga'
        ]));

        $syncData = [];
        foreach ($request->parameter as $param) {
            $syncData[$param['id']] = ['baku_mutu' => $param['baku_mutu']];
        }

        $kategori->parameter()->sync($syncData);

        return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Diupdate!');
    }

    //proses delete kategori
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->delete();

        if ($kategori) {
            return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Didelete!');
        }
    }
}
