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
            'tanggal_lahir' => 'required|date',
            'rt' => 'required|integer',
            'rw' => 'required|integer',
            'kode_pos' => 'required|integer',
            'alamat' => 'required|string',
            'username' => 'required|string',
            'no_telepon' => 'required|string|regex:/^\+[0-9]+$/',
            'email' => 'required|string|lowercase|email|max:255|unique:' . Customer::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customer = Customer::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tanggal_lahir' => $request->tanggal_lahir,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kode_pos' => $request->kode_pos,
            'alamat' => $request->alamat,
            'username' => $request->username,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        event(new Registered($customer));

        return Redirect::back()->with('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
    }
}
