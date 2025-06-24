<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Pengujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PengujianController extends Controller
{
    //lihat daftar jadwal pengujian
    public function index(Request $request)
    {
        $filterByStatus = $request->input('status');
        $filterByTanggal = $request->input('tanggal');

        $pengujian = Pengujian::with('form_pengajuan.instansi.user', 'user', 'kategori')
            ->when($filterByTanggal, function ($query) use ($filterByTanggal) {
                $query->whereDate('tanggal_uji', $filterByTanggal);
            })
            ->when($filterByStatus, function ($query) use ($filterByStatus) {
                $query->where('status', 'like', '%' . $filterByStatus . '%');
            })
            ->get();

        // Get pengajuan that are accepted but don't have pengujian scheduled yet
        $unscheduled_pengajuan = FormPengajuan::with('instansi', 'jadwal')
            ->where('status_pengajuan', 'diterima')
            ->whereDoesntHave('pengujian')
            ->get();

        return Inertia::render('pegawai/pengujian/Index', [
            'pengujian' => $pengujian,
            'unscheduled_pengajuan' => $unscheduled_pengajuan,
            'filter' => [
                'status' => $filterByStatus,
                'tanggal' => $filterByTanggal,
            ],
            'userRole' => Auth::user()->roles->pluck('name')->first(),
        ]);
    }

    //form tambah jadwal pengujian
    public function create()
    {
        $form_pengajuan = FormPengajuan::with('kategori.parameter', 'kategori.subkategori.parameter', 'instansi.user')
            ->where('status_pengajuan', 'diterima')
            ->whereHas('jadwal', function ($query) {
                $query->where('status', 'diterima');
            })
            ->whereDoesntHave('pengujian')
            ->get();

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
        $pengujian->load(['form_pengajuan.instansi.user', 'kategori', 'user']);

        $kategoriList = Kategori::select('id', 'nama')->get();
        $userList = User::role('teknisi')->select('id', 'nama')->get();
        $pengajuanList = FormPengajuan::with('instansi')
            ->where('status_pengajuan', 'diterima')
            ->select('id', 'kode_pengajuan', 'id_instansi')
            ->get();

        return Inertia::render('pegawai/pengujian/Edit', [
            'pengujian' => $pengujian,
            'kategoriList' => $kategoriList,
            'userList' => $userList,
            'pengajuanList' => $pengajuanList
        ]);
    }

    //proses update daftar pengujian
    public function update(Pengujian $pengujian, Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_form_pengajuan' => 'required|exists:form_pengajuan,id',
            'tanggal_uji' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'id_form_pengajuan.required' => 'Pengajuan Wajib Diisi.',
            'id_form_pengajuan.exists' => 'Pengajuan Tidak Valid.',
            'tanggal_uji.required' => 'Tanggal Mulai Wajib Diisi.',
            'tanggal_uji.date' => 'Tanggal Mulai Bertipe Tanggal.',
            'jam_mulai.date_format' => 'Format Jam Mulai Harus Dalam Format Jam:Menit.',
            'jam_selesai.date_format' => 'Format Jam Selesai Harus Dalam Format Jam:Menit.',
            'jam_selesai.after' => 'Format Jam Selesai Harus Setelah Jam Mulai.',
        ]);

        $form_pengajuan = FormPengajuan::with('jadwal')->find($request->id_form_pengajuan);
        $id_kategori = $form_pengajuan->id_kategori;

        if ($pengujian->status === 'selesai') {
            return redirect()->back()->with('error', 'Anda Tidak Bisa Mengupdate Pengujian Jika Status Pengujian Sudah Selesai');
        }

        if ($form_pengajuan->status_pengajuan !== 'diterima') {
            dd('Redirected because pengajuan not accepted');
            return redirect()->back()->with('error', 'Sebelum Melakukan Pengujian Harap Verifikasi Pengajuan Terlebih Dahulu!');
        }

        if (!$form_pengajuan->jadwal || $form_pengajuan->jadwal->status !== 'diterima') {
            dd('Redirected because jadwal not accepted or not exists');
            return redirect()->back()->with('error', 'Jadwal Belum Diterima. Tidak Bisa Menambahkan Pengujian.');
        }

        $pengujian->update([
            'id_form_pengajuan' => $request->id_form_pengajuan,
            'id_kategori' => $id_kategori,
            'id_user' => $user->id,
            'tanggal_uji' => $request->tanggal_uji,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return Redirect::route('pegawai.pengujian.detail', $pengujian->id)
            ->with('message', 'Pengujian berhasil diupdate');
    }

    //lihat detail daftar pengujian
    public function show(Pengujian $pengujian)
    {
        $pengujian->load(['form_pengajuan.instansi.user', 'kategori', 'user']);

        return Inertia::render('pegawai/pengujian/Detail', [
            'pengujian' => $pengujian
        ]);
    }

    public function verifikasi($id, Request $request)
    {
        $pengujian = Pengujian::findOrFail($id);

        $request->validate([
            'status' => 'required|in:selesai',
        ]);

        $pengujian->update([
            'status' => $request->status,
        ]);

        return Redirect::route('pegawai.pengujian.detail', $pengujian->id)
            ->with('message', 'Status pengujian berhasil diupdate');
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
