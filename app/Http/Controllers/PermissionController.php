<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index()
    {
        $permission = permissions::all();

        return Inertia::render('permission.index', [
            'permission' => $permission
        ]);
    }

    public function create()
    {
        return Inertia::render('permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $permission = permissions::create($request->all());

        if ($permission) 
        {
            return Redirect::route('permission.index')->with('message', 'Permission Berhasil Dibuat!');
        }
    }

    public function edit(permissions $permission)
    {
        return Inertia::render('permission.edit', [
            'permission' => $permission
        ]);
    }

    public function update(Request $request, permissions $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $permission->update($request->all());

        if($permission)
        {
            return Redirect::route('permission.index')->with('message', 'Permission Berhasil Diupdate!');
        }
    }

    public function destroy($id)
    {
        $permission = permissions::findOrFail($id);
        
        $permission->delete();

        if($permission)
        {
            return Redirect::route('permission.index')->with('message', 'Permission Berhasil Dihapus!');
        }
    }
}
