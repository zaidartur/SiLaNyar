<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PelangganController extends Controller
{
    public function index()
    {
        $customer = Customer::orderByDesc('created_at')
                    ->get();

        return Inertia::render('pegawai/pelanggan/index', [
            'customer' => $customer
        ]);
    }

    public function detail(Customer $customer)
    {
        return Inertia::render('pegawai/pelanggan/detail', [
            'customer' => $customer
        ]);
    }
}
