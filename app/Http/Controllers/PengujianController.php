<?php

namespace App\Http\Controllers;

use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\Pegawai;
use App\Models\Pengujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PengujianController extends Controller
{
    //lihat daftar jadwal pengujian
    public function index()
    {
        $pengujian = Pengujian::with('form_pengajuan', 'pegawai', 'kategori')->get();

        return Inertia::render('pegawai/pengujian/index', [
            'pengujian' => $pengujian
        ]);
    }

    //form tambah jadwal pengujian
    public function create()
    {
        $form_pengajuan = FormPengajuan::all();
        $pegawai = Pegawai::all();
        $kategori = Kategori::all();
        
        return Inertia::render('pegawai/pengujian/tambah', [
            'form_pengajuan' => $form_pengajuan,
            'pegawai' => $pegawai,
            'kategori' => $kategori
        ]);
    }

    //proses tambah jadwal pengujian
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_form_pengajuan' => 'required|exists:form_pengajuan,id',
            'id_pegawai' => 'required|exists:pegawai,id',
            'id_kategori' => 'required|exists:kategori,id',
            'tanggal_uji' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:diproses,selesai',
        ]);

        $pengujian = Pengujian::create($validated);

        if ($pengujian)
        {
            return Redirect::route('pegawai.pengujian.index')->with('message', 'Jadwal Pengujian Berhasil Dibuat!');
        }

        return back()->withErrors(['message' => 'Gagal membuat pengujian']);
    }

    //form edit jadwal pengujian
    public function edit(Pengujian $pengujian)
    {
        $form_pengajuan = FormPengajuan::all();
        $pegawai = Pegawai::all();
        $kategori = Kategori::all();

        return Inertia::render('pegawai/pengujian/edit', [
            'pengujian' => $pengujian,
            'form_pengajuan' => $form_pengajuan,
            'pegawai' => $pegawai,
            'kategori' => $kategori,
        ]);
    }

    //proses update daftar pengujian
    public function update(Pengujian $pengujian, Request $request)
    {
        if($pengujian->status === 'selesai')
        {
            return Redirect::back()->withErrors([
                'status' => 'Jadwal Yang Sudah Selesai Tidak Dapat Diubah!'
            ]);
        }

        $validated = $request->validate([
            'id_form_pengajuan' => 'required|exists:form_pengajuan,id',
            'id_pegawai' => 'required|exists:pegawai,id',
            'id_kategori' => 'required|exists:kategori,id',
            'tanggal_uji' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:diproses,selesai'
        ]);
        
        $pengujian->update($validated);

        if($pengujian)
        {
            return Redirect::route('pegawai.pengujian.index')->with('message', 'Pengujian Berhasil Diupdate');
        }
    }

    //lihat detail daftar pengujian
    public function show(Pengujian $pengujian)
    {
        $pengujian->load('form_pengajuan', 'pegawai', 'kategori');

        return Inertia::render('pegawai/pengujian/detail', [
            'pengujian' => $pengujian
        ]);
    }

    //proses hapus daftar pengujian
    public function destroy($id)
    {
        $pengujian = Pengujian::findOrFail($id);
        
        $pengujian->delete();

        if($pengujian)
        {
            return Redirect::route('pegawai.pengujian.index')->with('message', 'Jadwal Pengujian Berhasil Dihapus');
        }
    }
}
