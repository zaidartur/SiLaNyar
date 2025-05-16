<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{

    //lihat daftar customer
    public function create()
    {

        return Inertia::render('customer/Register');
    }

    //proses daftar customer
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'jabatan' => 'required|string|max:255',
            'no_telepon' => 'required|string|regex:/^\+[0-9]+$/',
            'email' => 'required|string|lowercase|email|max:255|unique:' . Customer::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customer = Customer::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        event(new Registered($customer));

        return Redirect::back()->with('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
    }
}
