<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pengujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $searchByStatus = $request->input('status');

        $customer = Auth::guard('customer')->user();

        $pengujian = Pengujian::whereHas('form_pengajuan', function($query) use ($customer, $searchByStatus)

        {
            $query->where('id_customer', $customer->id);  
            
            if($searchByStatus)
            {
                $query->where('status', 'like', '%'.$searchByStatus.'%');
            }
            
        })
        ->orderBy('tanggal_uji')
        ->with('form_pengajuan')
        ->get();

        return Inertia::render('customer/jadwal/index', [
            'pengujian' => $pengujian,
            'filter' => [
                'status' => $searchByStatus
            ],
        ]);
    }

    public function show($id)
    {
        $customer = Auth::guard('customer')->user();
        
        $pengujian = Pengujian::with('pegawai', 'kategori')
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
