<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\ParameterUji;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SubKategoriController extends Controller
{
    public function index()
    {
        $subkategori = SubKategori::with([
            'parameter' => function ($query) {
                $query->withPivot('baku_mutu');
            }
        ])
            ->get();

        return Inertia::render('pegawai/subkategori/Index', [
            'subkategori' => $subkategori
        ]);
    }

    public function create()
    {
        $parameter = ParameterUji::all();

        return Inertia::render('pegawai/subkategori/Tambah', [
            'parameter' => $parameter
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:subkategori,nama,',
            'parameter' => 'required|array',
            'parameter.*.id' => 'required|exists:parameter_uji,id',
            'parameter.*.baku_mutu' => 'required|string|max:255',
        ]);

        $subkategori = SubKategori::create($request->only([
            'nama'
        ]));

        $syncData = [];
        foreach ($request->parameter as $param) {
            $syncData[$param['id']] = ['baku_mutu' => $param['baku_mutu']];
        }

        $subkategori->parameter()->sync($syncData);

        return Redirect::route('pegawai.subkategori.index')->with('message', 'SubKategori Berhasil Ditambahkan!');
    }

    public function edit($id)
    {
        $subkategori = SubKategori::with(['parameter' => function ($query) {
            $query->withPivot('baku_mutu');
        }])->findOrFail($id);

        $parameter = ParameterUji::all()->map(function ($param) use ($subkategori) {
            $existing = $subkategori->parameter->firstWhere('id', $param->id);

            return [
                'id' => $param->id,
                'kode_parameter' => $param->kode_parameter,
                'nama_parameter' => $param->nama_parameter,
                'satuan' => $param->satuan,
                'harga' => $param->harga,
                'pivot' => $existing ? ['baku_mutu' => $existing->pivot->baku_mutu] : null
            ];
        });

        return Inertia::render('pegawai/subkategori/Edit', [
            'subkategori' => [
                'id' => $subkategori->id,
                'nama' => $subkategori->nama,
            ],
            'parameter' => $parameter
        ]);
    }

    public function update(SubKategori $subkategori, Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'parameter' => 'required|array',
            'parameter.*.id' => 'required|exists:parameter_uji,id',
            'parameter.*.baku_mutu' => 'required|string|max:255',
        ]);

        $subkategori->update($request->only([
            'nama'
        ]));

        $syncData = [];
        foreach ($request->parameter as $param) {
            $syncData[$param['id']] = ['baku_mutu' => $param['baku_mutu']];
        }

        $subkategori->parameter()->sync($syncData);

        return Redirect::route('pegawai.subkategori.index')->with('message', 'SubKategori Berhasil Ditambahkan!');
    }

    public function show($id)
    {
        $subkategori = SubKategori::with([
            'parameter' => function ($query) {
                $query->withPivot('baku_mutu');
            }
        ])

            ->get()
            ->findOrFail($id);

        return Inertia::render('pegawai/subkategori/Detail', [
            'subkategori' => $subkategori
        ]);
    }

    public function destroy($id)
    {
        $subkategori = SubKategori::findOrFail($id);

        $subkategori->delete();

        if ($subkategori) {
            return Redirect::route('pegawai.subkategori.index')->with('message', 'Kategori Berhasil Didelete!');
        }
    }
}
