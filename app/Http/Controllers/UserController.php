<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $user = User::with('roles')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        $semuarole = Role::all();

        return Inertia::render('superadmin/user/Index', [
            'users' => $user,
            'roles' => $semuarole,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function syncRoles(User $user, Request $request)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        $user->syncRoles($request->roles);

        return Redirect::back()->with('message', 'User dan Role berhasil di sinkronkan!');
    }
}
