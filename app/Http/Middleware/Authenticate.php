<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            if ($request->is('customer') || $request->is('customer/*')) {
                return route('customer.login');
            }

            if ($request->is('pegawai') || $request->is('pegawai/*')) {
                return route('pegawai.login');
            }

            return route('home'); // fallback jika tidak match
        }

        return null;
    }
}
