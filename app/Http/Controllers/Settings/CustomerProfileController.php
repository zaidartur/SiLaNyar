<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomerProfileController extends Controller
{
    public function show()
    {
        return Inertia::render('customer/profile/show', [
            'customer' => Auth::guard('customer')->user()
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
        $customer = $request->user('customer');
        
        $rules = [
            'nama' => 'required|string|max:255',
            'jenis_user' => 'required|in:instansi,perorangan',
            'alamat_pribadi' => 'nullable|string|max:255',
            'kontak_pribadi' => 'required|string|regex:/^\+[0-9]+$/',
            'email' => 'required|email|string|lowercase|unique:customer,email,'.$customer->id,
        ];

        // Tambah validasi untuk data instansi jika jenis_user adalah instansi
        if ($request->input('jenis_user') === 'instansi') {
            $rules = array_merge($rules, [
                'nama_instansi' => 'required|string|max:255',
                'tipe_instansi' => 'required|in:swasta,pemerintahan',
                'alamat_instansi' => 'required|string|max:255',
                'kontak_instansi' => 'required|string|regex:/^\+[0-9]+$/',
            ]);
        }

        $validated = $request->validate($rules);
        
        $customer->update($validated);

        return redirect()->route('customer.profile')
            ->with('message', 'Profil Berhasil Diubah!');
    }

    public function updatePassword(Request $request)
    {
        $customer = $request->user('customer');
        
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
        $customer = $request->user('customer');

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
