<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\pegawai;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = pegawai::all();
        
        return Inertia::render('superadmin/pegawai/index', [
            'pegawai' => $pegawai
        ]);
    }

    public function show(pegawai $pegawai)
    {
        return Inertia::render('superadmin/pegawai/detail', [
            'pegawai' => $pegawai
        ]);
    }
}
