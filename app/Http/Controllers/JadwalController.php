<?php

namespace App\Http\Controllers;

use App\Models\jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = jadwal::latest()->get();

        return Inertia::render('jadwal/index', [
            'jadwal' => $jadwal
        ]);    
    }

    public function create()
    {
        return Inertia::render('jadwal/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_form_pengujian' => 'required',
            'waktu_pengambilan' => 'required|date',
            'lokasi' => 'required',
            'status' => 'required|in:diproses,selesai',
            'keterangan' => 'required'
        ]);

        $jadwal = jadwal::create($request->all());

        if($jadwal) {
            return Redirect::route('admin.jadwal.index')->with('message', 'Jadwal Berhasil Dibuat');
        }
    }

    public function edit()
    {
        
    }
}
