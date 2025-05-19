<?php

namespace App\Http\Controllers\Auth\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Roles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['roles', 'permissions']);

        return Inertia::render('superadmin/pegawai/Index', [
            'pegawai' => $pegawai
        ]);
    }
    public function create()
    {
        $role = Roles::all();
        
        return Inertia::render('superadmin/pegawai/Registrasi', [
            'role' => $role,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon' => 'required|string|regex:/^\+[0-9]{1,3}$/',
            'email' => 'required|string|lowercase|email|max:255|unique:'.Pegawai::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_role' => 'required|exists:roles,name'
        ]);

        $pegawai = Pegawai::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'jabatan' => $request->jabatan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telepon' => $request->no_telepon,
        ]);

        $role = Roles::findOrFail($request->role);
        $pegawai->assignRole($role);
        
        event(new Registered($pegawai));
        
        return Redirect::back()->with('message', 'Akun Berhasil Terdaftar');
    }
}
