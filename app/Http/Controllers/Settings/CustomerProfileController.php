<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Instansi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomerProfileController extends Controller
{
    public function show()
    {
        $customer = auth()->guard('customer')->user();

        $instansi = Instansi::where('id_customer', $customer->id)
            ->get();

        return Inertia::render('customer/profile/show', [
            'customer' => $customer,
            'instansi' => $instansi,
        ]);
    }

    public function storeOrUpdateInstansi(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:swasta,pemerintahan',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|regex:/^\+[0-9]+$/|string',
            'email' => 'required|string|lowercase|email|max:255|unique' . Instansi::class,
        ]);

        $instansi = Instansi::updateOrCreate(
            ['id_customer' => $customer->id],
            [
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email
            ]
        );

        return Redirect::back()->with('message', 'Instansi Berhasil Ditambah!');
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

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'jabatan' => 'required|string|max:255',
            'no_telepon' => 'required|string|regex:/^\+[0-9]+$/',
            'email' => 'required|email|string|lowercase|unique:customer,email,' . $customer->id,
        ]);

        $customer->update($request->all());

        return redirect()->route('customer.profile.index')
            ->with('message', 'Profil Berhasil Diubah!');
    }

    public function updatePassword(Request $request)
    {
        $customer = $request->user('customer');

        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|confirmed',
        ]);

        if (!Hash::check($request->password_lama, $customer->password)) {
            return Redirect::back()->withErrors(['password_lama' => 'Password saat ini salah.']);
        }

        $customer->password = bcrypt($request->password_baru);
        $customer->save();

        return Redirect::route('customer.profile.index')->with('message', 'Password Berhasil Diubah!');
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
