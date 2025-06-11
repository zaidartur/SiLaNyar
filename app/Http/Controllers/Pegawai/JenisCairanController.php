<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JenisCairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JenisCairanController extends Controller
{
    public function index()
    {
        $jenis_cairan = JenisCairan::all();
        return Inertia::render('pegawai/jenis_cairan/Index', [
            'jenis_cairan' => $jenis_cairan
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'batas_minimum' => 'required|numeric',
            'batas_maksimum' => 'nullable|numeric|after_or_equal:batas_minimum'
        ]);

        if (!is_null($request->batas_maksimum) && $request->batas_minimum >= $request->batas_maksimum) {
            return back()->withErrors([
                'batas_minimum' => 'Batas Minimum harus lebih kecil dari Batas Maksimum.',
                'batas_maksimum' => 'Batas Maksimum harus lebih besar dari Batas Minimum.',
            ])->withInput();
        }

        $jenis_cairan = JenisCairan::create($request->all());

        if ($jenis_cairan) {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Ditambahkan');
        }
    }

    public function update(JenisCairan $jenis_cairan, Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'batas_minimum' => 'required|numeric',
            'batas_maksimum' => 'numeric|after_or_equal:batas_minimum'
        ]);

        if ($request->batas_minimum >= $request->batas_maksimum && $request->batas_maksimum <= $request->batas_minimum) {
            return Redirect::back()->withErrors([
                'batas_minimum' => 'Batas Minimum Tidak Boleh Kurang Dari Batas Maksimum',
                'batas_maksimum' => 'Batas Maksimum Tidak Boleh Lebih Dari Batas Minimum'
            ]);
        }

        $jenis_cairan->update($request->all());

        if ($jenis_cairan) {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Diedit!');
        }
    }

    public function destroy($id)
    {
        $jenis_cairan = JenisCairan::findOrFail($id);

        $jenis_cairan->delete();

        if ($jenis_cairan) {
            return Redirect::route('pegawai.jenis_cairan.index')->with('message', 'Jenis Cairan Berhasil Dihapus!');
        }
    }
}
