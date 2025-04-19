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
        return Inertia::render('customer/register');
    }

    //proses daftar customer 
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_user' => 'required|in:instansi,perorangan',
            'alamat_pribadi' => 'nullable|string|max:255',
            'kontak_pribadi' => 'required|string|regex:/^\+[0-9]{1,3}$/',
            'nama_instansi' => 'nullable|string|max:255',
            'tipe_instansi' => 'nullable|in:swasta,pemerintahan',
            'alamat_instansi' => 'nullable|string|max:255',
            'kontak_instansi' => 'nullable|string|regex:/^\+[0-9]{1,3}$/',
            'email' => 'required|string|lowercase|email|max:255|unique:'.Customer::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);   

        $customer = Customer::create($request->all());

        event(new Registered($customer));

        Auth::login($customer);

        return Redirect::back()->with('message', 'Akun Berhasil Terdaftar Harap Tunggu Verifikasi Data!');
    }
}
