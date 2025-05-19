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
use Illuminate\Support\Facades\Http;

class CustomerProfileController extends Controller
{
    private function user()
    {
        if (!session()->has('access_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $response = Http::withoutVerifying()->withToken(session('access_token'))
            ->withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'https://example-app.com/'
            ])
            ->get(config('services.sso.api_user_url'));

        return $response->json();
    }

    public function show()
    {
        $userData = $this->user();

        if (isset($userData['error'])) {
            return redirect()->route('login')->with('error', 'Unauthorized');
        }

        $customer = auth()->guard('customer')->user();
        $instansiData = Instansi::where('id_customer', $customer->id)->get();

        return Inertia::render('customer/profile/Show', [
            'user' => $userData,
            'instansi' => $instansiData
        ]);
    }

    public function showInstansi(Instansi $instansi)
    {
        $customer = auth()->guard('customer')->user();
        $instansi->where('id_customer', $customer->id)->get();

        return Inertia::render('customer/profile/ShowInstansi', [
            'instansi' => $instansi
        ]);
    }

    public function storeInstansi(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:swasta,pemerintahan',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|regex:/^\+[0-9]+$/|string',
            'email' => 'required|string|lowercase|email|max:255|unique' . Instansi::class,
        ]);

        $instansi = Instansi::create(
            ['id_customer' => $customer->id],
            [
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email
            ]
        );

        if (!$instansi) {
            return Redirect::back()->withError('Validasi Salah');
        }
        return Redirect::back()->with('message', 'Instansi Berhasil Ditambah!');
    }

    public function editInstansi(Instansi $instansi)
    {
        $customer = auth()->guard('customer')->user();

        $instansi->where('id_customer', $customer)->get();

        return Inertia::render('customer/profile/EditInstansi', [
            'instansi' => $instansi
        ]);
    }

    public function updateIntansi(Instansi $instansi, Request $request)
    {
        $customer = auth()->guard('customer')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:swasta,pemerintahan',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|regex:/^\+[0-9]+$/|string',
            'email' => 'required|string|lowercase|email|max:255|unique' . Instansi::class,
        ]);

        $instansi->update(
            ['id_customer' => $customer->id],
            [
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email
            ]
        );

        return Redirect::route('customer.profile.instansi.detail')->with('message', 'Data Instansi Berhasil Diupdate!');
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
