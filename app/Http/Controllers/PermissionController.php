<?php

namespace App\Http\Controllers;

use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index()
    {
        $permission = Permissions::all();

        return Inertia::render('superadmin/permission/Index', [
            'permission' => $permission
        ]);
    }
}
