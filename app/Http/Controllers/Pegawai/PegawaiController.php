<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        
        return Inertia::render('superadmin/pegawai/Index', [
            'pegawai' => $pegawai
        ]);
    }

    public function show(Pegawai $pegawai)
    {
        return Inertia::render('superadmin/pegawai/Detail', [
            'pegawai' => $pegawai
        ]);
    }
}
