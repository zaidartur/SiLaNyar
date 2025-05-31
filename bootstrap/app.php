<?php

use App\Console\Commands\UpdateHasilUjiStatus;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SSOAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'check.permission' => CheckPermission::class,
            'role' => CheckRole::class,
        ]);
    })
    ->withCommands([
        UpdateHasilUjiStatus::class,
    ])
    ->withSchedule(function (Schedule $schedule)
    {
        $schedule->command('app:update-hasil-uji-status')->everyMinute();    
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
