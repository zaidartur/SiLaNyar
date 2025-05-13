<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as BaseMiddleware;
use Illuminate\Http\Request;

class Authenticate extends BaseMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            if ($request->is('pegawai/*') || $request->is('pegawai')) {
                return route('pegawai.login');
            }

            if ($request->is('customer/*') || $request->is('customer')) {
                return route('customer.login');
            }
        }
        return null;
    }
}
