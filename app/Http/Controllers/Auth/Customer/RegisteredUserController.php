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
        return Inertia::render('customer/registrasi');
    }

    //proses daftar customer
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_user' => 'required|in:instansi,perorangan',
            'alamat_pribadi' => 'nullable|string|max:255',
            'kontak_pribadi' => 'required|string|regex:/^\+[0-9]+$/',
            'nama_instansi' => 'nullable|string|max:255',
            'tipe_instansi' => 'nullable|in:swasta,pemerintahan',
            'alamat_instansi' => 'nullable|string|max:255',
            'kontak_instansi' => 'nullable|string|regex:/^\+[0-9]+$/',
            'email' => 'required|string|lowercase|email|max:255|unique:' . Customer::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->jenis_user === 'instansi') {
            $request->validate([
                'nama_instansi' => 'required|string|max:255',
                'tipe_instansi' => 'required|in:swasta,pemerintahan',
                'alamat_instansi' => 'required|string|max:255',
                'kontak_instansi' => 'required|string|regex:/^\+[0-9]+$/',
            ]);
        }

        $customer = Customer::create([
            'nama' => $request->nama,
            'jenis_user' => $request->jenis_user,
            'alamat_pribadi' => $request->alamat_pribadi,
            'kontak_pribadi' => $request->kontak_pribadi,
            'nama_instansi' => $request->nama_instansi,
            'tipe_instansi' => $request->tipe_instansi,
            'alamat_instansi' => $request->alamat_instansi,
            'kontak_instansi' => $request->kontak_instansi,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status_verifikasi' => 'diproses',
        ]);

        event(new Registered($customer));

        return Redirect::back()->with('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
    }
}
