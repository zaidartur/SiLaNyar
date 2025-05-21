<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        // Gunakan Auth facade untuk mengecek user dan permission
        if (!Auth::guard('web')->check() || !Auth::guard('web')->user()->hasPermissionTo($permission)) {
            abort(403, 'Role Anda Tidak Mendapatkan Izin Untuk Mengelola Fitur '.$permission.', Tolong Hubungi Super Admin Untuk Menambahkan Izin '.$permission.' Di Role Yang Anda Miliki!');
        }

        return $next($request);
    }
}
