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

        if (!$user) {
            return Redirect::route('sso.login');
        }

        $expandedRoles = [];
        $aliasMap = [
            'customer' => ['customer', 'pelanggan'],
            'pelanggan' => ['pelanggan', 'customer'],
            'admin' => ['admin', 'staf_administrator'],
            'staf_administrator' => ['staf_administrator', 'admin'],
            'teknisi' => ['teknisi', 'analis'],
            'analis' => ['analis', 'teknisi'],
        ];

        foreach ($roles as $role) {
            if (isset($aliasMap[$role])) {
                $expandedRoles = array_merge($expandedRoles, $aliasMap[$role]);
            } else {
                $expandedRoles[] = $role;
            }
        }
        $expandedRoles = array_unique($expandedRoles);

        if (!$user->hasAnyRole($expandedRoles)) {
            $rolesString = implode(', ', $roles);
            abort(403, 'Role Anda Bukan ' . $rolesString  . ' Gunakan Role Yang Tepat Atau Hubungi Super Admin Untuk Memberikan Permission Di Role Yang Anda Miliki');
        }

        return $next($request);
    }
}
