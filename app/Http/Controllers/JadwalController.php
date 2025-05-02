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
    public function index(Request $request)
    {
        $filterByStatus = $request->input('status');
        $filterByTanggal = $request->input('waktu_pengambilan');

        $jadwal = jadwal::with('form_pengajuan')
                    ->when($filterByTanggal, function ($query) use ($filterByTanggal)
                    {
                        $query->whereDate('waktu_pengambilan', $filterByTanggal);    
                    })
                    ->when($filterByStatus, function ($query) use ($filterByStatus) 
                    {
                        $query->where('status', 'like', '%'.$filterByStatus.'%');    
                    })
                    ->get();
        return Inertia::render('pegawai/jadwal/index', [
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
        $form_pengajuan = form_pengajuan::get();
        return Inertia::render('pegawai/jadwal/tambah', [
            'form_pengajuan' => $form_pengajuan
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
            'status' => 'required|in:diproses,selesai',
            'keterangan' => 'required|string|max:255'
        ]);

        $jadwal = jadwal::create($request->all());

        if($jadwal) {
            return Redirect::route('pegawai.jadwal.index')->with('message', 'Jadwal Berhasil Dibuat!');
        }
    }

    //form edit jadwal
    public function edit(jadwal $jadwal)
    {
        $form_pengajuan = form_pengajuan::latest()->get();

        return Inertia::render('pegawai/jadwal/edit', [
            'jadwal' => $jadwal,
            'form_pengajuan' => $form_pengajuan,
        ]);
    }

    //proses update jadwal
    public function update(jadwal $jadwal, Request $request)
    {
        if($jadwal->status === 'selesai')
        {
            return Redirect::back()->withErrors([
                'status' => 'Jadwal Yang Sudah Selesai Tidak Dapat Diubah!'
            ]);
        }

        $batasEditTanggal = $jadwal->waktu_pengambilan->copy()->subDay();

        if(now()->greaterThan($batasEditTanggal))
        {
            return Redirect::back()->withErrors([
                'waktu_pengambilan' => 'Jadwal Tidak Dapat Diganti Karena Sudah Melewati Batas Reschedule!'
            ]);
        }
        
        $request->validate([
            'id_form_pengajuan' => 'nullable',
            'waktu_pengambilan' => 'required|date',
            'status' => 'required|in:diproses,selesai',
            'keterangan' => 'required|string|max:255'
        ]);

        $updated = $jadwal->update($request->all());

        if($updated)
        {
            return Redirect::route('pegawai.jadwal.index')->with('message', 'Jadwal Berhasil Diupdate!');
        }
    }

    //proses hapus jadwal
    public function destroy($id)
    {
        $jadwal = jadwal::findOrFail($id);
        
        $jadwal->delete();

        if($jadwal)
        {
            return Redirect::route('pegawai.jadwal.index')->with('message', 'Jadwal Berhasil Dihapus!');
        }
    }

    public function show(jadwal $jadwal)
    {
        $jadwal->load(['form_pengajuan', 'pegawai']);
        
        return Inertia::render('jadwal/show', [
            'jadwal' => $jadwal
        ]);
    }
}
