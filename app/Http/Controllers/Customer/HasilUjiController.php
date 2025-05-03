<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HasilUji;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HasilUjiController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $hasil_uji = HasilUji::with(['parameter', 'pengujian'])->get();

        return Inertia::render('customer/hasil_uji/Index', [
            'customer' => $customer,
            'hasil_uji' => $hasil_uji
        ]);
    }

    public function show(HasilUji $hasil_uji)
    {
        $customer = Auth::guard('customer')->user();
        $hasil_uji->load('parameter', 'pengujian');
        
        return Inertia::render('customer/hasil_uji/Show', [
            'customer' => $customer,
            'hasil_uji' => $hasil_uji
        ]);
    }

    public function convert(HasilUji $hasil_uji)
    {
        $hasil_uji->load('parameter', 'pengujian.form_pengajuan.customer');

        $pdf = PDF::loadview("hasil_uji.show", [
            'hasil_uji' => $hasil_uji,
            'tanggal' => now()->format('d-m-Y'),
        ]);

        return $pdf->download('hasil_uji_'.$hasil_uji->id.'.pdf');
    }
}
