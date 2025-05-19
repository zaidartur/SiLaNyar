<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if(!$user)
        {
            return Redirect::route('sso.login');
        }

        if(!$user->hasAnyRole($roles))
        {
            abort(403, 'Tidak Dapat Mengakses Fitur Ini!');
        }
        
        return $next($request);
    }
}
