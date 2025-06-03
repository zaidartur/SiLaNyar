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

        $user = Auth::user();
        $instansiData = Instansi::where('id_user', $user->id)->get();

        return Inertia::render('customer/profile/Index', [
            'user' => $userData,
            'instansi' => $instansiData
        ]);
    }

    public function showInstansi(Instansi $instansi)
    {
        $user = Auth::user();
        $instansi->where('id_user', $user->id)->get();

        return Inertia::render('customer/profile/ShowInstansi', [
            'instansi' => $instansi
        ]);
    }

    public function storeInstansi(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:swasta,pemerintahan,pribadi',
            'alamat' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
            'desa_kelurahan' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:instansi,email',
            'no_telepon' => ['required', 'string', 'regex:/^(08|\+62|62)[0-9]{7,13}$/'],
            'posisi_jabatan' => 'required|string|max:255',
            'departemen_divisi' => 'required|string|max:255',
        ]);

        $instansi = Instansi::create([
            'id_user' => $user->id,
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'alamat' => $request->alamat,
            'wilayah' => $request->wilayah,
            'desa_kelurahan' => $request->desa_kelurahan,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'posisi_jabatan' => $request->posisi_jabatan,
            'departemen_divisi' => $request->departemen_divisi
        ]);

        if (!$instansi) {
            return Redirect::back()->withError('Mohon Isi Form Instansi Dengan Benar!');
        }

        return Redirect::route('customer.profile')->with('message', 'Instansi Berhasil Ditambah!');
    }

    public function editInstansi(Instansi $instansi)
    {
        $user = Auth::user();

        $instansi->where('id_user', $user)->get();

        return Inertia::render('customer/profile/EditInstansi', [
            'instansi' => $instansi
        ]);
    }

    public function updateIntansi(Instansi $instansi, Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:swasta,pemerintahan',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|regex:/^\+[0-9]+$/|string',
            'email' => 'required|string|lowercase|email|max:255|unique' . Instansi::class,
        ]);

        $instansi->update(
            ['id_user' => $user->id],
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
}
