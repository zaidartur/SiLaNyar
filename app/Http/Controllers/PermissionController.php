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

    public function create()
    {
        return Inertia::render('superadmin/permission/Tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $permission = Permissions::create($request->all());

        if ($permission) 
        {
            return Redirect::route('superadmin.permission.index')->with('message', 'Permission Berhasil Dibuat!');
        }
    }

    public function edit(Permissions $permission)
    {
        return Inertia::render('superadmin/permission/Edit', [
            'permission' => $permission
        ]);
    }

    public function update(Request $request, Permissions $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $permission->update($request->all());

        if($permission)
        {
            return Redirect::route('superadmin.permission.index')->with('message', 'Permission Berhasil Diupdate!');
        }
    }

    public function destroy($id)
    {
        $permission = Permissions::findOrFail($id);
        
        $permission->delete();

        if($permission)
        {
            return Redirect::route('superadmin.permission.index')->with('message', 'Permission Berhasil Dihapus!');
        }
    }
}
