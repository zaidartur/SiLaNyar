<?php

namespace App\Http\Middleware;

use App\Models\customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckVerifiedCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('customer')->check())
        {
            $user = Auth::guard('customer')->user();

            if($user->status_verifikasi !== "diterima")
            {
                Auth::guard('customer')->logout();

                return Redirect::route('login')->withErrors([
                    'email' => 'Akun Anda Belum Diverifikasi Oleh Admin'
                ]);
            }
        }
        
        return $next($request);
    }
}
