<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class KategoriController extends Controller
{
    //lihat daftar kategori
    public function index()
    {
        $kategori = Kategori::with([
            'subkategori.parameter' => function ($query) {
                $query->withPivot('baku_mutu');
            },
            'parameter' => function ($query) {
                $query->withPivot('baku_mutu');
            }
        ])
            ->get();

        $kategori = $kategori->map(function ($item) {
            if ($item->subkategori->isNotEmpty()) {
                $finalParameters = collect();
                foreach ($item->subkategori as $sub) {
                    foreach ($sub->parameter as $param) {
                        $finalParameters->push([
                            'id' => $param->id,
                            'nama_parameter' => $param->nama_parameter,
                            'baku_mutu' => $param->pivot->baku_mutu,
                        ]);
                    }
                }
            } else {
                $finalParameters = $item->parameter->map(function ($param) {
                    return [
                        'id' => $param->id,
                        'nama_parameter' => $param->nama_parameter,
                        'baku_mutu' => $param->pivot->baku_mutu,
                    ];
                });
            }

            // Return kategori + tambahkan final_parameter
            return [
                'id' => $item->id,
                'kode_kategori' => $item->kode_kategori,
                'nama' => $item->nama,
                'harga' => $item->harga,
                'subkategori' => $item->subkategori,
                'parameter' => $finalParameters, // gunakan di frontend
            ];
        });

        return Inertia::render('pegawai/kategori/Index', [
            'kategori' => $kategori,
        ]);
    }

    //form tambah kategori
    public function create()
    {
        $subkategori = SubKategori::all();
        $parameter = ParameterUji::all();

        return Inertia::render('pegawai/kategori/Tambah', [
            'parameter' => $parameter,
            'subkategori' => $subkategori
        ]);
    }

    //proses tambah kategori
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kategori,nama',
            'harga' => 'required|numeric|min:0',
            'subkategori' => 'nullable|array',
            'subkategori.*' => 'required|exists:subkategori,id',
            'parameter' => 'nullable|array',
            'parameter.*.id' => 'required|exists:parameter_uji,id',
            'parameter.*.baku_mutu' => 'required_with:parameter.*.id|string|max:255',
        ], [
            'nama.required' => 'Nama Kategori Wajib Diisi.',
            'nama.unique' => 'Nama Kategori Tidak Boleh Sama.',
            'harga.required' => 'Harga Wajib Diisi.',
            'harga.numeric' => 'Harga Harus Berupa Angka.',
            'harga.min' => 'Harga Tidak Boleh Kurang Dari 0.',
            'subkategori.*.required' => 'Sub Kategori Tidak Valid.',
            'subkategori.*.exists' => 'Sub Kategori Tidak Ditemukan.',
            'parameter.*.id.required' => 'Parameter Wajib Dipilih.',
            'parameter.*.id.exists' => 'Parameter Tidak Valid.',
            'parameter.*.baku_mutu.required_with' => 'Baku Mutu Wajib Diisi Untuk Parameter Yang Dipilih.',
        ]);

        if (
            empty($request->subkategori) && empty($request->parameter)
        ) {
            return Redirect::back()
                ->withErrors(['subkategori' => 'Pilih Minimal Satu Sub Kategori atau Parameter']);
        }

        $kategori = Kategori::create($request->only([
            'nama',
            'harga',
        ]));

        $kategori->subkategori()->sync($request->subkategori ?? []);

        if (is_array($request->parameter)) {
            $syncData = [];
            foreach ($request->parameter as $param) {
                $syncData[$param['id']] = ['baku_mutu' => $param['baku_mutu']];
            }
            $kategori->parameter()->sync($syncData);
        } else {
            $kategori->parameter()->sync([]);
        }

        return Redirect::route('pegawai.kategori.index')->with('message', 'Kategori Berhasil Ditambahkan!');
    }

    //form edit kategori
    public function edit($id)
    {
        $kategori = Kategori::with(['subkategori', 'parameter' => function ($q) {
            $q->withPivot('baku_mutu');
        }])->findOrFail($id);

        $subkategori = SubKategori::all();

        $parameter = ParameterUji::all()->map(function ($param) use ($kategori) {
            $existing = $kategori->parameter->firstWhere('id', $param->id);

            return [
                'id' => $param->id,
                'kode_parameter' => $param->kode_parameter,
                'nama_parameter' => $param->nama_parameter,
                'satuan' => $param->satuan,
                'harga' => $param->harga,
                'pivot' => $existing ? ['baku_mutu' => $existing->pivot->baku_mutu] : null
            ];
        });

        return Inertia::render('pegawai/kategori/Edit', [
            'kategori' => [
                'id' => $kategori->id,
                'nama' => $kategori->nama,
                'harga' => $kategori->harga,
                'subkategori' => $kategori->subkategori
            ],
            'subkategori' => $subkategori,
            'parameter' => $parameter,
        ]);
    }

    //proses update kategori
    public function update(Kategori $kategori, Request $request)
    {
        $rules = [
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'subkategori' => 'nullable|array',
            'subkategori.*' => 'required|exists:subkategori,id',
            'parameter' => 'nullable|array',
            'parameter.*.id' => 'required|exists:parameter_uji,id',
            'parameter.*.baku_mutu' => 'required_with:parameter.*.id|string|max:255'
        ];

        if ($request->nama != $kategori->nama) {
            $rules['nama'] .= '|unique:kategori,nama';
        }

        if (
            empty($request->subkategori) && empty($request->parameter)
        ) {
            return Redirect::back()
                ->withErrors(['subkategori' => 'Pilih Minimal Satu Sub Kategori atau Parameter']);
        }

        $validatedData = $request->validate($rules, [
            'nama.required' => 'Nama Kategori Wajib Diisi.',
            'nama.unique' => 'Nama Kategori Tidak Boleh Sama.',
            'harga.required' => 'Harga Wajib Diisi.',
            'harga.numeric' => 'Harga Harus Berupa Angka.',
            'harga.min' => 'Harga Tidak Boleh Kurang Dari 0.',
            'subkategori.*.required' => 'Sub Kategori Tidak Valid.',
            'subkategori.*.exists' => 'Sub Kategori Tidak Ditemukan.',
            'parameter.*.id.required' => 'Parameter Wajib Dipilih.',
            'parameter.*.id.exists' => 'Parameter Tidak Valid.',
            'parameter.*.baku_mutu.required' => 'Baku Mutu Wajib Diisi Untuk Parameter Yang Dipilih.',
        ]);

        $kategori->update([
            'nama' => $validatedData['nama'],
            'harga' => $validatedData['harga'],
        ]);

        $kategori->subkategori()->sync($request->subkategori ?? []);

        if (is_array($request->parameter)) {
            $syncData = [];
            foreach ($request->parameter as $param) {
                $syncData[$param['id']] = ['baku_mutu' => $param['baku_mutu']];
            }
            $kategori->parameter()->sync($syncData);
        } else {
            $kategori->parameter()->sync([]);
        }

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
