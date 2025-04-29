<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth('pegawai')->user;

        if(!$user || !$user->can($permission))
        {
            abort(403, 'Anda Tidak Memiliki Akses Ke Halaman Ini');
        }

        return $next($request);
    }
}
