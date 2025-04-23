<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckVerifiedPegawai
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('pegawai')->check())
        {
            $user = Auth::guard('pegawai')->user();

            if($user->status_verifikasi !== 'diterima')
            {
                Auth::guard('pegawai')->logout();

                return Redirect::route('pegawai/login')->withErrors([
                    'email' => 'Akun Anda Belum Diverifikasi Oleh Super Admin'
                ]);
            }
        }

        return $next($request);
    }
}
