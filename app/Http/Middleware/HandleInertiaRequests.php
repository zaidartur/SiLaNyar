<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => function () use ($request) {
                    if (! $user = $request->user()) {
                        return null;
                    }
                    $user->loadMissing('roles');
                    return $user;
                },
                'roles' => fn() => $request->user()?->getRoleNames() ?? [],
                'permissions' => fn() => $request->user()?->getAllPermissions()->pluck('name') ?? [],
                'is_pegawai' => fn() => $request->user()?->hasRole([
                    'superadmin', 'kepala_dinas', 'kepala_lab', 'pengendali_teknis',
                    'penyelia', 'staf_administrator', 'analis', 'ppcu', 'admin', 'teknisi'
                ]) ?? false,
                'is_customer' => fn() => $request->user()?->hasRole(['pelanggan', 'customer']) ?? false,
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
