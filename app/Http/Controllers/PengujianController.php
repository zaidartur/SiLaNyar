<?php

namespace App\Http\Controllers;

use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\Pegawai;
use App\Models\Pengujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PengujianController extends Controller
{
    //lihat daftar jadwal pengujian
    public function index()
    {
        $pengujian = Pengujian::with('form_pengajuan', 'pegawai', 'kategori')->get();

        return Inertia::render('pegawai/pengujian/Index', [
            'pengujian' => $pengujian
        ]);
    }

    //form tambah jadwal pengujian
    public function create()
    {
        $form_pengajuan = FormPengajuan::all();
        $pegawai = Pegawai::all();
        $kategori = Kategori::all();

        return Inertia::render('pegawai/pengujian/Tambah', [
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
            'id_kategori' => 'required|exists:kategori,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
        ]);

        $tanggalMulai = Carbon::parse($request->input('tanggal_mulai'));
        $tanggalSelesai = Carbon::parse($request->input('tanggal_selesai'));

        $tanggalSaatIni = $tanggalMulai->copy();

        $sukses = false;

        while ($tanggalSaatIni->lte($tanggalSelesai)) {
            if (!in_array($tanggalSaatIni->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                Pengujian::create([
                    'id_form_pengajuan' => $request->id_form_pengajuan,
                    'id_pegawai' => $request->id_pegawai,
                    'id_kategori' => $request->id_kategori,
                    'tanggal_uji' => $tanggalSaatIni->format('d-m-Y'),
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'status' => 'diproses'
                ]);

                $sukses = true;
            }

            $tanggalSaatIni->addDay();
        }

        if (!$sukses) {
            return back()->withErrors(['message' => 'Gagal membuat pengujian']);
        }

        return Redirect::route('pegawai.pengujian.index')->with('message', 'Jadwal Pengujian Berhasil Dibuat!');
    }

    //form edit jadwal pengujian
    public function edit(Pengujian $pengujian)
    {
        $form_pengajuan = FormPengajuan::all();
        $pegawai = Pegawai::all();
        $kategori = Kategori::all();

        return Inertia::render('pegawai/pengujian/Edit', [
            'pengujian' => $pengujian,
            'form_pengajuan' => $form_pengajuan,
            'pegawai' => $pegawai,
            'kategori' => $kategori,
        ]);
    }

    //proses update daftar pengujian
    public function update(Pengujian $pengujian, Request $request)
    {
        if ($pengujian->status === 'selesai') {
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

        if ($pengujian) {
            return Redirect::route('pegawai.pengujian.index')->with('message', 'Pengujian Berhasil Diupdate');
        }
    }

    //lihat detail daftar pengujian
    public function show(Pengujian $pengujian)
    {
        $pengujian->load('form_pengajuan', 'pegawai', 'kategori');

        return Inertia::render('pegawai/pengujian/Detail', [
            'pengujian' => $pengujian
        ]);
    }

    //proses hapus daftar pengujian
    public function destroy($id)
    {
        $pengujian = Pengujian::findOrFail($id);

        $pengujian->delete();

        if ($pengujian) {
            return Redirect::route('pegawai.pengujian.index')->with('message', 'Jadwal Pengujian Berhasil Dihapus');
        }
    }
}
