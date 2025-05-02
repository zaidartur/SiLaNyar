<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Pegawai;

class PegawaiProfileController extends Controller
{
    public function show()
    {
        $pegawai = Auth::guard('pegawai')->user();
        return Inertia::render('pegawai/profile/show', [
            'pegawai' => $pegawai
        ]);
    }

    public function edit()
    {
        $pegawai = Auth::guard('pegawai')->user();
        return Inertia::render('pegawai/profile/edit', [
            'pegawai' => $pegawai
        ]);
    }
    

    public function update(Request $request)
    {
        $pegawai = $request->user('pegawai');
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|in:Admin Lab,Kepala Lab,Analis Kimia,Analis Mikrobiologi,Analis Air,Manajer Teknis,Staff Lab',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon' => 'required|string|regex:/^\+[0-9]+$/',
            'email' => 'required|string|lowercase|max:255|email|unique:pegawai,email,'.$pegawai->id
        ]);

        $pegawai->update($request->only([
            'nama',
            'jabatan',
            'jenis_kelamin',
            'no_telepon',
            'email'
        ]));

        return Redirect::route('pegawai.profile')->with('message', 'Profile Berhasil Diubah!');
    }

    public function destroy(Request $request)
    {
        $pegawai = $request->user('pegawai');

        $request->validate([
            'password' => ['required'],
        ]);

        if(!Hash::check($request->password, $pegawai->password ))
        {
            return Redirect::back()->withErrors(['password' => 'Password Yang Anda Masukkan Salah!']);
        }

        Auth::guard('pegawai')->logout();

        $pegawai->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('pegawai.login')->with('message', 'Akun Berhasil Dihapus');
    }
}
