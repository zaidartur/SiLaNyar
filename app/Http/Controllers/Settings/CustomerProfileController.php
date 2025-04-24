<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomerProfileController extends Controller
{   
    public function show()
    {
        $customer = Auth::guard('customer')->user();
        return Inertia::render('customer/profile/show', [
            'customer' => $customer
        ]);
    }

    public function edit()
    {
        $customer = Auth::guard('customer')->user();
        return Inertia::render('customer/profile/edit', [
            'customer' => $customer
        ]);
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_user' => 'required|in:instansi,perorangan',
            'alamat_pribadi' => 'nullable|string|max:255',
            'kontak_pribadi' => 'required|string|regex:/^\+[0-9]+$/',
            'nama_instansi' => 'nullable|string|max:255',
            'tipe_instansi' => 'nullable|in:swasta,pemerintahan',
            'alamat_instansi' => 'nullable|string|max:255',
            'kontak_instansi' => 'nullable|string|regex:/^\+[0-9]+$/',
        ]);

        if ($request->jenis_user === 'instansi') {
            $request->validate([
                'nama_instansi' => 'required|string|max:255',
                'tipe_instansi' => 'required|in:swasta,pemerintahan',
                'alamat_instansi' => 'required|string|max:255',
                'kontak_instansi' => 'required|string|regex:/^\+[0-9]+$/',
            ]);
        }

        $customer->update($request->only([
            'nama',
            'jenis_user',
            'alamat_pribadi',
            'kontak_pribadi',
            'nama_instansi',
            'tipe_instansi',
            'alamat_instansi',
            'kontak_instansi',
        ]));

        return Redirect::route('customer.profile')->with('message', 'Profil Berhasil Diubah!');
    }

    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|confirmed',
        ]);

        if(!Hash::check($request->password_lama, $customer->password)) {
            return Redirect::back()->withErrors(['password_lama' => 'Password saat ini salah.']);
        }

        $customer->password = bcrypt($request->password_baru);
        $customer->save();

        return Redirect::route('customer.profile')->with('message', 'Password Berhasil Diubah!');
    }

    public function destroy(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'password' => ['required'],
        ]);

        if (!Hash::check($request->password, $customer->password)) {
            return Redirect::back()->withErrors(['password' => 'Password Yang Anda Masukkan Salah']);
        }

        Auth::guard('customer')->logout();

        $customer->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('/')->with('message', 'Akun Berhasil Dihapus');
    }
}
