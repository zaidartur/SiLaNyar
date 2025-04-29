<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\pengujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JadwalController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        $pengujian = pengujian::whereHas('form_pengajuan', function($query) use ($customer)
        {
            $query->where('id_customer', $customer->id);    
        })
        ->orderBy('tanggal_uji')
        ->get();

        return Inertia::render('customer/jadwal/index', [
            'pengujian' => $pengujian
        ]);
    }

    public function show($id)
    {
        $customer = Auth::guard('customer')->user();
        
        $pengujian = pengujian::with('pegawai', 'kategori')
        ->whereHas('form_pengajuan', function($query) use ($customer)
        {
            $query->where('id_customer', $customer->id);    
        })
        ->where('id', $id)
        ->firstOrFail();

        return Inertia::render('customer/jadwal/detail', [
            'pengujian' => $pengujian
        ]);
    }
}
