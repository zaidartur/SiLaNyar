<?php

namespace App\Http\Controllers;

use App\Models\form_pengajuan;
use App\Models\kategori;
use App\Models\pegawai;
use App\Models\pengujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PengujianController extends Controller
{
    //lihat daftar jadwal pengujian
    public function index()
    {
        $pengujian = pengujian::with('form_pengajuan', 'pegawai', 'kategori')->get();

        return Inertia::render('pengujian/index', [
            'pengujian' => $pengujian
        ]);
    }

    //form tambah jadwal pengujian
    public function create()
    {
        $form_pengajuan = form_pengajuan::all();
        $pegawai = pegawai::all();
        $kategori = kategori::all();
        
        return Inertia::render('pengujian/create', [
            'form_pengajuan' => $form_pengajuan,
            'pegawai' => $pegawai,
            'kategori' => $kategori
        ]);
    }

    //proses tambah jadwal pengujian
    public function store(Request $request)
    {
        $request->validate([
            'id_form_pengajuan' => 'required|exists:form_pengajuan,id',
            'id_pegawai' => 'required|exists:pegawai,id',
            'kategori' => 'required|exists:kategori,id',
            'tanggal_uji' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:diproses,selesai'
        ]);

        $pengujian = pengujian::create($request->all());

        if ($pengujian)
        {
            return Redirect::route('pengujian.index')->with('message', 'Jadwal Pengujian Berhasil Dibuat!');
        }
    }

    //form edit jadwal pengujian
    public function edit(pengujian $pengujian)
    {
        $form_pengajuan = form_pengajuan::all();
        $pegawai = pegawai::all();
        $kategori = kategori::all();

        return Inertia::render('pengujian/edit', [
            'pengujian' => $pengujian,
            'form_pengajuan' => $form_pengajuan,
            'pegawai' => $pegawai,
            'kategori' => $kategori,
        ]);
    }

    //proses update daftar pengujian
    public function update(pengujian $pengujian, Request $request)
    {
        $request->validate([
            'id_form_pengajuan' => 'required|exists:form_pengajuan,id',
            'id_pegawai' => 'required|exists:pegawai,id',
            'id_kategori' => 'required|exists:kategori,id',
            'tanggal_uji' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:diproses,selesai'
        ]);
        
        $pengujian = pengujian::update($request->all());

        if($pengujian)
        {
            return Redirect::route('pengujian.index')->with('message', 'Pengujian Berhasil Diupdate');
        }
    }

    //lihat detail daftar pengujian
    public function show(pengujian $pengujian)
    {
        $pengujian->load('form_pengajuan', 'pegawai', 'kategori');

        return Inertia::render('pengujian/show', [
            'pengujian' => $pengujian
        ]);
    }

    //proses hapus daftar pengujian
    public function destroy($id)
    {
        $pengujian = pengujian::findOrFail($id);
        
        $pengujian->delete();

        if($pengujian)
        {
            return Redirect::route('pengujian.index')->with('message', 'Jadwal Pengujian Berhasil Dihapus');
        }
    }
}
