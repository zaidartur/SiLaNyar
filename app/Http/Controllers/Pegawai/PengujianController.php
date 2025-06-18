<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\User;
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
        $pengujian = Pengujian::with('form_pengajuan.instansi.user', 'user', 'kategori')->get();

        return Inertia::render('pegawai/pengujian/Index', [
            'pengujian' => $pengujian,
            'filter' => request()->all()
        ]);
    }

    //form tambah jadwal pengujian
    public function create()
    {
        $form_pengajuan = FormPengajuan::with('kategori.parameter', 'kategori.subkategori.parameter', 'instansi.user')->get();
        $user = User::role('teknisi')->select('id', 'nama')->get();

        return Inertia::render('pegawai/pengujian/Tambah', [
            'form_pengajuan' => $form_pengajuan,
            'user' => $user,
        ]);
    }

    //proses tambah jadwal pengujian
    public function store(Request $request)
    {
        $request->validate([
            'id_form_pengajuan' => 'required|exists:form_pengajuan,id',
            'id_user' => 'required|exists:users,id',
            'id_kategori' => 'required|exists:kategori,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
        ], [
            'id_form_pengajuan.required' => 'Pengajuan Wajib Diisi.',
            'id_form_pengajuan.exists' => 'Pengajuan Tidak Valid.',
            'id_user.required' => 'User Wajib Diisi.',
            'id_user.exists' => 'User Tidak Valid.',
            'id_kategori.required' => 'Kategori Wajib Diisi.',
            'id_kategori.exists' => 'Kategori Tidak Valid.',
            'tanggal_mulai.required' => 'Tanggal Mulai Wajib Diisi.',
            'tanggal_mulai.date' => 'Tanggal Mulai Bertipe Tanggal.',
            'tanggal_selesai.required' => 'Tanggal Selesai Wajib Diisi.',
            'tanggal_selesai.date' => 'Tanggal Selesai Bertipe Tanggal.',
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai Tidak Boleh Sebelum Tanggal Mulai.',
            'jam_mulai.date_format' => 'Format Jam Mulai Harus Dalam Format Jam:Menit.',
            'jam_selesai.date_format' => 'Format Jam Selesai Harus Dalam Format Jam:Menit.',
            'jam_selesai.after' => 'Format Jam Selesai Harus Setelah Jam Mulai.',
        ]);

        $form_pengajuan = FormPengajuan::find($request->id_form_pengajuan);

        if ($form_pengajuan->status_pengajuan !== 'diterima') {
            return redirect()->back()->with('error', 'Sebelum Melakukan Pengujian Harap Verifikasi Pengajuan Terlebih Dahulu!');
        }

        $tanggalMulai = Carbon::parse($request->input('tanggal_mulai'));
        $tanggalSelesai = Carbon::parse($request->input('tanggal_selesai'));

        $tanggalSaatIni = $tanggalMulai->copy();

        $sukses = false;

        while ($tanggalSaatIni->lte($tanggalSelesai)) {
            if (!in_array($tanggalSaatIni->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                Pengujian::create([
                    'id_form_pengajuan' => $request->id_form_pengajuan,
                    'id_user' => $request->id_user,
                    'id_kategori' => $request->id_kategori,
                    'tanggal_uji' => $tanggalSaatIni->format('Y-m-d'),
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'status' => 'diproses'
                ]);

                $sukses = true;
            }

            $tanggalSaatIni->addDay();
        }

        if (!$sukses) {
            return back()->withErrors(['error' => 'Gagal membuat pengujian']);
        }

        return Redirect::route('pegawai.pengujian.index')->with('message', 'Jadwal Pengujian Berhasil Dibuat!');
    }

    //form edit jadwal pengujian
    public function edit(Pengujian $pengujian)
    {
        $pengujian->load(['form_pengajuan.kategori.parameter', 'form_pengajuan.kategori.subkategori.parameter', 'form_pengajuan.instansi.user', 'user']);

        $form_pengajuan = FormPengajuan::select('id', 'kode_pengajuan', 'id_instansi', 'id_kategori')
            ->with('kategori:id,nama')
            ->with('instansi:id,nama')
            ->with('instansi.user:id,nama')
            ->get();

        $user = User::role('teknisi')->select('id', 'nama')->get();

        return Inertia::render('pegawai/pengujian/Edit', [
            'pengujian' => $pengujian,
            'form_pengajuan' => $form_pengajuan,
            'user' => $user,
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

        $request->validate([
            'id_form_pengajuan' => 'required|exists:form_pengajuan,id',
            'id_user' => 'required|exists:users,id',
            'id_kategori' => 'required|exists:kategori,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:diproses,selesai',
        ], [
            'id_form_pengajuan.required' => 'Pengajuan Wajib Diisi.',
            'id_form_pengajuan.exists' => 'Pengajuan Tidak Valid.',
            'id_user.required' => 'User Wajib Diisi.',
            'id_user.exists' => 'User Tidak Valid.',
            'id_kategori.required' => 'Kategori Wajib Diisi.',
            'id_kategori.exists' => 'Kategori Tidak Valid.',
            'tanggal_mulai.required' => 'Tanggal Mulai Wajib Diisi.',
            'tanggal_mulai.date' => 'Tanggal Mulai Bertipe Tanggal.',
            'tanggal_selesai.required' => 'Tanggal Selesai Wajib Diisi.',
            'tanggal_selesai.date' => 'Tanggal Selesai Bertipe Tanggal.',
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai Tidak Boleh Sebelum Tanggal Mulai.',
            'jam_mulai.date_format' => 'Format Jam Mulai Harus Dalam Format Jam:Menit.',
            'jam_selesai.date_format' => 'Format Jam Selesai Harus Dalam Format Jam:Menit.',
            'jam_selesai.after' => 'Format Jam Selesai Harus Setelah Jam Mulai.',
        ]);

        $pengajuan = FormPengajuan::find($request->id_form_pengajuan);

        if ($pengajuan->status_pengajuan !== 'diterima') {
            return redirect()->back()->with('error', 'Sebelum Melakukan Pengujian Harap Verifikasi Pengajuan Terlebih Dahulu!');
        }

        // Hati-hati: delete semua pengujian dengan kombinasi ini
        Pengujian::where('id_form_pengajuan', $pengujian->id_form_pengajuan)
            ->where('id_kategori', $pengujian->id_kategori)
            ->delete();

        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);

        $last = Pengujian::orderBy('kode_pengujian', 'desc')->first();
        $lastNumber = 0;

        if ($last && preg_match('/DJ-(\d+)/', $last->kode_pengujian, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $tanggalSaatIni = $tanggalMulai->copy();

        $sukses = false;

        while ($tanggalSaatIni->lte($tanggalSelesai)) {
            if (!in_array($tanggalSaatIni->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $lastNumber++;
                Pengujian::create([
                    'id_form_pengajuan' => $request->id_form_pengajuan,
                    'id_user' => $request->id_user,
                    'id_kategori' => $request->id_kategori,
                    'tanggal_uji' => $tanggalSaatIni->format('Y-m-d'),
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'status' => $request->status,
                ]);
                $sukses = true;
            }

            $tanggalSaatIni->addDay();
        }

        if (!$sukses) {
            return back()->withErrors(['error' => 'Tidak ada hari kerja pada rentang tanggal yang dipilih.']);
        }

        return Redirect::route('pegawai.pengujian.index')->with('message', 'Pengujian Berhasil Diupdate');
    }


    //lihat detail daftar pengujian
    public function show(Pengujian $pengujian)
    {
        $pengujian->load(['kategori', 'form_pengajuan.kategori.parameter', 'form_pengajuan.kategori.subkategori.parameter', 'form_pengajuan.instansi.user', 'user']);

        // $kategori = $pengujian->form_pengajuan->kategori;

        // $parameterKategori = $kategori->parameter->map(function ($param) {
        //     return [
        //         'id' => $param->id,
        //         'nama' => $param->nama_parameter,
        //         'satuan' => $param->satuan,
        //         'baku_mutu' => $param->pivot->baku_mutu ?? null,
        //     ];
        // });

        // $parameterSubKategori = $kategori->subkategori->flatMap(function ($sub) {
        //     return $sub->parameter->map(function ($param) {
        //         return [
        //             'id' => $param->id,
        //             'nama' => $param->nama_parameter,
        //             'satuan' => $param->satuan,
        //             'baku_mutu' => $param->pivot->baku_mutu ?? null,
        //         ];
        //     });
        // });

        // $semuaParameter = $parameterKategori->merge($parameterSubKategori)->unique('id')->values();
        return Inertia::render('pegawai/pengujian/Detail', [
            'pengujian' => $pengujian,
            // 'parameter' => $semuaParameter,
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
