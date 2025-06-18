<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
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

    // //form tambah parameter
    // public function create()
    // {
    //     return Inertia::render('pegawai/parameter/Tambah');
    // }

    //proses tambah parameter
    public function store(Request $request)
    {
        $request->validate([
            'nama_parameter' => 'required|string|unique:parameter_uji,nama_parameter',
            'satuan' => 'required|string',
            'harga' => 'required|numeric|min:0',
        ], [
            'nama_parameter.required' => 'Nama Parameter Wajib Diisi.',
            'nama_parameter.unique' => 'Nama Parameter Sudah Digunakan. Harap Pilih Nama Lain.',
            'satuan.required' => 'Satuan Wajib Diisi.',
            'harga.required' => 'Harga Wajib Diisi.',
            'harga.numeric' => 'Harga Harus Bertipe Angka.',
            'harga.min' => 'Harga Tidak Boleh Kurang Dari 0.',
        ]);

        $parameter = ParameterUji::create($request->all());

        if ($parameter) {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Dibuat!');
        }
    }

    // //form edit parameter
    // public function edit(ParameterUji $parameter)
    // {
    //     return Inertia::render('pegawai/parameter/Edit', [
    //         'parameter' => $parameter
    //     ]);
    // }

    //proses update parameter
    public function update(ParameterUji $parameter, Request $request)
    {
        $rules =[
            'nama_parameter' => 'required|string',
            'satuan' => 'required|string',
            'harga' => 'required|numeric|min:0',
        ];

        if ($request->nama_parameter != $parameter->nama_parameter) {
            $rules['nama_parameter'] .= '|unique:parameter_uji,nama_parameter';
        }

        $validatedData = $request->validate($rules, [
            'nama_parameter.required' => 'Nama Parameter Wajib Diisi.',
            'nama_parameter.unique' => 'Nama Parameter Sudah Digunakan. Harap Pilih Nama Lain.',
            'satuan.required' => 'Satuan Wajib Diisi.',
            'harga.required' => 'Harga Wajib Diisi.',
            'harga.numeric' => 'Harga Harus Bertipe Angka.',
            'harga.min' => 'Harga Tidak Boleh Kurang Dari 0.',
        ]);

        $parameter->update($validatedData);

        if ($parameter) {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Diupdate!');
        }
    }

    //proses hapus parameter
    public function destroy($id)
    {
        $parameter = ParameterUji::findOrFail($id);

        $parameter->delete();

        if ($parameter) {
            return Redirect::route('pegawai.parameter.index')->with('message', 'Parameter Berhasil Dihapus!');
        }
    }
}
