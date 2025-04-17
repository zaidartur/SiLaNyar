<?php

namespace App\Http\Controllers;

use App\Models\form_pengajuan;
use App\Models\jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JadwalController extends Controller
{
    //lihat daftar jadwal
    public function index()
    {
        $jadwal = jadwal::with('form_pengajuan')->get();

        return Inertia::render('jadwal/index', [
            'jadwal' => $jadwal,
        ]);    
    }

    //form tambah jadwal
    public function create()
    {
        $form_pengajuan = form_pengajuan::get();
        return Inertia::render('jadwal/create', [
            'form_pengajuan' => $form_pengajuan
        ]);
    }

    //proses tambah jadwal
    public function store(Request $request)
    {
        $request->validate([
            'id_form_pengujian' => 'required|exists:form_pengujain,id',
            'waktu_pengambilan' => 'required|date',
            'status' => 'required|in:diproses,selesai',
            'keterangan' => 'required|string|max:255'
        ]);

        $jadwal = jadwal::create($request->all());

        if($jadwal) {
            return Redirect::route('test.jadwal.index')->with('message', 'Jadwal Berhasil Dibuat!');
        }
    }

    //form edit jadwal
    public function edit(jadwal $jadwal)
    {
        $form_pengajuan = form_pengajuan::latest()->get();

        return Inertia::render('jadwal/edit', [
            'jadwal' => $jadwal,
            'form_pengajuan' => $form_pengajuan,
        ]);
    }

    //proses update jadwal
    public function update(jadwal $jadwal, Request $request)
    {
        $request->validate([
            'id_form_pengajuan' => 'nullable',
            'waktu_pengambilan' => 'required|date',
            'status' => 'required|in:diproses,selesai',
            'keterangan' => 'required|string|max:255'
        ]);

        $jadwal = jadwal::update($request->all());

        if($jadwal)
        {
            return Redirect::route('test.jadwal.index')->with('message', 'Jadwal Berhasil Diupdate!');
        }
    }

    //proses hapus jadwal
    public function destroy($id)
    {
        $jadwal = jadwal::findOrFail($id);
        
        $jadwal->delete();

        if($jadwal)
        {
            return Redirect::route('test.jadwal.index')->with('message', 'Jadwal Berhasil Dihapus!');
        }
    }
}
