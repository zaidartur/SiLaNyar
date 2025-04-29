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
        $role = roles::with('permissions')->get(); // Ubah dari permission ke permissions

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
            if (roles::where('name', $request->name)->where('guard_name', 'pegawai')->exists()) {
                return back()->withErrors([
                    'name' => 'The name has already been taken.'
                ])->withInput();
            }

            $role = roles::create([
                'name' => $request->name,
                'guard_name' => 'pegawai'
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return redirect('/superadmin/role')
                ->with('message', 'Role Berhasil Ditambahkan!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'name' => 'An error occurred while creating the role.'
            ])->withInput();
        }
    }

    public function edit(roles $role)
    {
        $permission = permissions::all();

        return Inertia::render('role.edit', [
            'roles' => $role->load('permissions'), // Ubah dari 'permission' ke 'permissions'
            'permissions' => $permission
        ]);
    }

    public function update(Request $request, roles $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id' // Ubah dari permission ke permissions
        ]);

        $role->update([
            'name' => $request->name, // Ubah dari nama ke name untuk konsistensi
            'guard_name' => 'pegawai'
        ]);

        if (isset($request->permissions)) {
            $role->syncPermissions($request->permissions); // Gunakan syncPermissions dari Spatie
        }

        return redirect('/superadmin/role')->with('message', 'Role Berhasil Diupdate!');
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
