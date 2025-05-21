<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\HasilUji;
use App\Models\HasilUjiHistori;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use App\Notifications\HasilUjiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HasilUjiController extends Controller
{
    //lihat list hasil uji
    public function index()
    {
        $hasil_uji = HasilUji::with('parameter', 'pengujian', 'kategori');

        return Inertia::render('pegawai/hasil_uji/Index', [
            'hasil_uji' => $hasil_uji
        ]);
    }

    //form tambah hasil uji
    public function create()
    {
        $parameter = ParameterUji::all();
        $pengujian = Pengujian::all();

        return Inertia::render('pegawai/hasil_uji/Tambah', [
            'parameter' => $parameter,
            'pengujian' => $pengujian,
        ]);
    }

    //proses tambah hasil uji
    public function store(Request $request)
    {
        $request->validate([
            'id_parameter' => 'required|exists:parameter_uji,id',
            'id_pengujian' => 'required|exists:pengujian,id',
            'nilai' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $hasil_uji = HasilUji::create([
            'id_parameter' => $request->id_parameter,
            'id_pengujian' => $request->id_pengujian,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'status' => 'draft'
        ]);

        if ($hasil_uji) {
            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Dibuat!');
        }
    }

    //form edit hasil uji
    public function edit(HasilUji $hasil_uji)
    {
        $hasil_uji->load(['parameter', 'pengujian']);

        $parameter = ParameterUji::all();
        $pengujian = Pengujian::all();

        return Inertia::render('pegawai/hasil_uji/Edit', [
            'hasil_uji' => $hasil_uji,
            'parameter' => $parameter,
            'pengujian' => $pengujian
        ]);
    }

    //proses update hasil uji
    public function update(HasilUji $hasil_uji, Request $request)
    {
        $request->validate([
            'id_parameter' => 'required|exists:parameter_uji,id',
            'id_pengujian' => 'required|exists:pengujian,id',
            'nilai' => 'required|numeric',
            'keterangan' => 'required|string|max:255',
            'status' => 'required|in:acc,revisi,draft'
        ]);

        if ($hasil_uji->status !== 'acc') {
            HasilUjiHistori::create([
                'id_hasil_uji' => $hasil_uji->id,
                'id_parameter' => $request->id_parameter,
                'id_pengujian' => $request->id_pengujian,
                'nilai' => $request->nilai,
                'keterangan' => $request->keterangan,
                'status' => $request->status,
                'id_user' => Auth::id()
            ]);
        }

        $hasil_uji->update($request->all());

        if ($hasil_uji->status === 'acc') {
            $customer = $hasil_uji->pengujian->form_pengajuan->customer;
            $customer->notify(new HasilUjiNotification($hasil_uji));
        }

        if ($hasil_uji) {
            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Diupdate!');
        }
    }

    //lihat detail hasil uji
    public function show(HasilUji $hasil_uji)
    {
        $hasil_uji->load(['parameter', 'pengujian']);

        return Inertia::render('pegawai/hasil_uji/Detail', [
            'hasil_uji' => $hasil_uji
        ]);
    }

    //lihat riwayat hasil uji
    public function riwayat($id)
    {
        $hasil_uji = HasilUji::with(['riwayat'])->findOrFail($id);

        return Inertia::render(
            'pegawai/hasil_uji/Riwayat',
            [
                'riwayat' => $hasil_uji->riwayat,
                'id_hasil_uji' => $hasil_uji->id,
            ]
        );
    }

    //hapus hasil uji
    public function destroy($id)
    {
        $hasil_uji = HasilUji::findOrFail($id);

        $hasil_uji->delete();

        if ($hasil_uji) {
            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Dihapus!');
        }
    }
}
