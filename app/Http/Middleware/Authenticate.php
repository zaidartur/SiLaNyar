<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
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
