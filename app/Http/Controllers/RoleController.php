<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use App\Models\roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $role = roles::with('permission');

        return Inertia::render('role', [
            'role' => $role
        ]);
    }

    public function create()
    {
        $permission = permissions::all();
        
        return Inertia::render('role.create', [
            'permission' => $permission
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permission,id'
        ]);

        $role = roles::create([
            'name' => $request->nama
        ]);

        $role->permission()->sync($request->permission);

        if($role)
        {
            return Redirect::route('role.index')->with('message', 'Role Berhasil Ditambahkan!');
        }
    }

    public function edit(roles $role)
    {
        $permission = permissions::all();

        return Inertia::render('role.edit', [
            'roles' => $role->load('permission'),
            'permissions' => $permission
        ]);
    }

    public function update(Request $request, roles $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permission,id'
        ]);

        $role->update([
            'name' => $request->nama
        ]);

        $role->permission()->sync($request->permission);

        if ($role)
        {
            return Redirect::route('role.index')->with('message', 'Role Berhasil Diupdate!');
        }
    }

    public function destroy($id)
    {
        $role = roles::findOrFail($id);
        
        $role->delete();

        if($role)
        {
            return Redirect::route('role.index')->with('message', 'Role Berhasil Dihapus!');
        }
    }
}
