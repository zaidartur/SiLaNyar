<?php

namespace App\Http\Controllers;

use App\Models\Permissions;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $role = Roles::with('permissions')->get(); // Ubah dari permission ke permissions

        return Inertia::render('superadmin/role/Index', [
            'role' => $role
        ]);
    }

    public function create()
    {
        $permission = Permissions::all();

        return Inertia::render('superadmin/role/Tambah', [
            'permission' => $permission
        ]);
    }

    public function store(Request $request)
    {
        // Validasi dulu sebelum try-catch
        $request->validate([
            'name' => 'required|string|max:255',  // Pisahkan unique validation
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.'
        ]);

        try {
            // Cek unique setelah validasi dasar
            if (Roles::where('name', $request->name)->where('guard_name', 'web')->exists()) {
                return back()->withErrors([
                    'name' => 'Nama Sudah Dipakai.'
                ])->withInput();
            }

            $role = Roles::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return Redirect::route('superadmin.role.index')
                ->with('message', 'Role Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'name' => 'An error occurred while creating the role.'
            ])->withInput();
        }
    }

    public function edit(Roles $role)
    {
        $permission = Permissions::all();

        return Inertia::render('superadmin/role/Edit', [
            'roles' => $role->load('permissions'), // Ubah dari 'permission' ke 'permissions'
            'permissions' => $permission
        ]);
    }

    public function update(Request $request, Roles $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id' // Ubah dari permission ke permissions
        ]);

        $role->update([
            'name' => $request->name, // Ubah dari nama ke name untuk konsistensi
            'guard_name' => 'web'
        ]);

        if (isset($request->permissions)) {
            $role->syncPermissions($request->permissions); // Gunakan syncPermissions dari Spatie
        }

        return Redirect::route('superadmin.role.index')->with('message', 'Role Berhasil Diupdate!');
    }

    public function destroy($id)
    {
        $role = Roles::findOrFail($id);

        $role->delete();

        if ($role) {
            return Redirect::route('superadmin.role.index')->with('message', 'Role Berhasil Dihapus!');
        }
    }
}
