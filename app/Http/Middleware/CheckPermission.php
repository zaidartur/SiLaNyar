<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('pegawai')->user();
        
        if (!$user) abort(403);

        if(!$user)
        {
            abort(403, 'Anda Tidak Memiliki Akses Ke Halaman Ini');
        }

        return $next($request);
    }
}
