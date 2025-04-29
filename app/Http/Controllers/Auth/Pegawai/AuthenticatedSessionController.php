<?php

namespace App\Http\Controllers\Auth\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PegawaiLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{

    //lihat login pegawai
    public function create()
    {
        return Inertia::render('pegawai/login');
    }

    //proses login pegawai
    public function store(PegawaiLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return Redirect::route('pegawai.dashboard');
    }

    //proses logout pegawai
    public function destroy(Request $request)
    {
        Auth::guard('pegawai')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('pegawai/');
    }
}
