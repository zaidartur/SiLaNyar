<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use App\Models\HasilUji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AduanController extends Controller
{
    public function create(HasilUji $hasil_uji)
    {
        $customer = Auth::guard('customer')->user();

        if(!$hasil_uji->id_customer !== $customer->id)
        {
            abort(403, 'Anda Tidak Memiliki Akses Di Aduan Ini!');
        }

        return Inertia::render('Customer/Aduan/Create', [
            'hasil_uji' => $hasil_uji,
            'customer' => $customer
        ]);
    }

    public function store(HasilUji $hasil_uji, Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        if(!$hasil_uji->id_customer !== $customer->id)
        {
            abort(403, 'Anda Tidak Memiliki Akses Di Aduan Ini!');
        }

        $request->validate([
            'masalah' => 'required|string',
            'perbaikan' => 'required|string',
        ]);

        $aduan = Aduan::create([
            'id_hasil_uji' => $hasil_uji->id,
            'id_customer' => $customer->id,
            'masalah' => $request->masalah,
            'perbaikan' => $request->perbaikan
        ]);

        return Redirect::route('customer.hasil_uji.index')->with('message', 'Aduan Berhasil Terkirim');
    }
}
