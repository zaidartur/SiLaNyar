<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\FormPengajuan;
use App\Models\Jadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JadwalController extends Controller
{
    //lihat daftar jadwal
    public function index(Request $request)
    {
        $filterByStatus = $request->input('status');
        $filterByTanggal = $request->input('waktu_pengambilan');
        $filterByMetode = $request->input('metode_pengambilan');

        $jadwal = Jadwal::with('form_pengajuan.instansi.user', 'user')
            ->when($filterByTanggal, function ($query) use ($filterByTanggal) {
                $query->whereDate('waktu_pengambilan', $filterByTanggal);
            })
            ->when($filterByStatus, function ($query) use ($filterByStatus) {
                $query->where('status', 'like', '%' . $filterByStatus . '%');
            })
            ->when($filterByMetode, function ($query) use ($filterByMetode) {
                $query->where('metode_pengambilan', 'like', '%' . $filterByMetode . '%');
            })
            ->get();

        return Inertia::render('pegawai/pengambilan/Index', [
            'jadwal' => $jadwal,
            'filter' => [
                'status' => $filterByStatus,
                'tanggal' => $filterByTanggal,
            ],
        ]);
    }

    //form tambah jadwal
    public function create()
    {
        $form_pengajuan = FormPengajuan::with('instansi')->where('metode_pengambilan', 'diambil')->get();
        $user = User::whereHas('roles', function ($query) {
            $query->where('name', 'teknisi');
        })->get();

        return Inertia::render('pegawai/pengambilan/Tambah', [
            'form_pengajuan' => $form_pengajuan,
            'user' => $user
        ]);
    }

    //proses tambah jadwal
    public function store(Request $request)
    {
        $request->validate([
            'id_form_pengajuan' => [
                'required',
                'exists:form_pengajuan,id',
                'unique:jadwal,id_form_pengajuan'
            ],
            'waktu_pengambilan' => 'required|date|after_or_equal:today',
            'keterangan' => 'required|string|max:255'
        ]);

        $pengajuan = FormPengajuan::findOrFail($request->id_form_pengajuan);

        if ($pengajuan->metode_pengambilan !== 'diambil') {
            return Redirect::back()->withErrors([
                'metode_pengambilan' => 'Jadwal Hanya Bisa Dibuat Ketika Customer Memilih Diambil'
            ]);
        }

        $jadwal = Jadwal::create($request->all());

        if ($jadwal) {
            return Redirect::route('pegawai.pengambilan.index')->with('message', 'Jadwal Berhasil Dibuat!');
        }
    }

    //form edit jadwal
    public function edit(Jadwal $jadwal)
    {
        $jadwal->load('form_pengajuan', 'user');

        $pengajuan = FormPengajuan::findOrFail($jadwal->id_form_pengajuan);

        if ($jadwal->status === 'selesai') {
            return Redirect::back()->withErrors(['status' => 'Jadwal Yang Sudah Selesai Tidak Dapat Diubah!']);
        }

        $form_pengajuan = FormPengajuan::where('metode_pengambilan', 'diambil')->latest()->get();

        return Inertia::render('pegawai/pengambilan/Edit', [
            'jadwal' => $jadwal,
            'form_pengajuan' => $form_pengajuan,
        ]);
    }

    // Proses update jadwal
    public function update(Jadwal $jadwal, Request $request)
    {
        if ($jadwal->status === 'selesai') {
            return Redirect::back()->withErrors([
                'status' => 'Jadwal Yang Sudah Selesai Tidak Dapat Diubah!'
            ]);
        }

        $pengajuan = FormPengajuan::findOrFail($jadwal->id_form_pengajuan);

        if ($pengajuan->metode_pengambilan === 'diantar') {
            $request->validate([
                'status' => 'required|in:diproses,selesai'
            ]);

            $jadwal->update([
                'status' => $request->status,
            ]);

            return $jadwal->update
                ? Redirect::route('pegawai.pengambilan.index')->with('message', 'Jadwal Berhasil Diupdate')
                : Redirect::back()->withErrors('error', 'Gagal Melakukan Edit Jadwal');
        }

        $tanggalBaru = $request->waktu_pengambilan;
        $tanggalLama = $jadwal->waktu_pengambilan->toDateString();
        $isWaktuBerubah = $tanggalBaru !== $tanggalLama;

        $rules = [
            'status' => 'required|in:diproses,selesai',
            'keterangan' => 'nullable|string|max:255'
        ];

        if ($isWaktuBerubah) {
            $rules['waktu_pengambilan'] = 'required|date|after:today';
        } else {
            $rules['waktu_pengambilan'] = 'required|date';
        }

        $request->validate($rules);

        if ($isWaktuBerubah) {
            $jadwalLama = Carbon::parse($jadwal->waktu_pengambilan)->startOfDay();
            $sekarang = Carbon::now()->startOfDay();

            if ($jadwalLama->lessThanOrEqualTo($sekarang)) {
                return Redirect::back()->withErrors([
                    'waktu_pengambilan' => 'Jadwal Tidak Dapat Diganti Karena Sudah Melewati Batas Reschedule!'
                ]);
            }
        }

        $updated = $jadwal->update([
            'waktu_pengambilan' => $request->waktu_pengambilan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return $updated
            ? Redirect::route('pegawai.pengambilan.index')->with('message', 'Jadwal Berhasil Diupdate!')
            : Redirect::back()->withErrors(['error' => 'Gagal mengupdate jadwal!']);
    }


    //proses hapus jadwal
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        if ($jadwal->status === 'diproses') {
            return Redirect::back()->withErrors('Jadwal Tidak Dapat Dihapus, Jika Status Masih Proses');
        }

        if ($jadwal->form_pengajuan->metode_pengambilan === 'diantar') {
            return Redirect::back()->withErrors('Jadwal Tidak Dapat Dihapus, Karena Metode Pengambilan Diantar');
        }

        $jadwal->delete();

        if ($jadwal) {
            return Redirect::route('pegawai.pengambilan.index')->with('message', 'Jadwal Berhasil Dihapus!');
        }
    }

    //lihat detail jadwal
    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['form_pengajuan.instansi.user', 'user']);

        return Inertia::render('pegawai/pengambilan/Detail', [
            'jadwal' => $jadwal
        ]);
    }
}
