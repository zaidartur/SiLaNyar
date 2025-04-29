<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{

    //lihat login customer
    public function create()
    {
        return Inertia::render('customer/login');
    }

    //proses login customer
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return Redirect::route('dashboard');
    }

    //proses logout customer
    public function destroy(Request $request)
    {
        Auth::guard('customer')->logout(); 
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('/');
    }
}
