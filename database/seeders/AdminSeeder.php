<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Pegawai::create([
            'nama' => 'Admin Test',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminsilanyar'),
            'jabatan' => 'admin',
            'jenis_kelamin' => 'laki-laki',
            'no_telepon' => '+6285741358179'
        ]);

        $role = Roles::firstOrCreate(['name' => 'admin'], ['guard_name' => 'pegawai']);

        $admin->assignRole('admin');

        $adminPermissions = [
            'lihat-pengajuan', 'detail-pengajuan', 'edit-pengajuan',
            'lihat-pengujian', 'detail-pengujian', 'tambah-pengujian', 'edit-pengujian', 'delete-pengujian',
            'lihat-pengambilan', 'detail-pengambilan', 'tambah-pengambilan', 'edit-pengambilan', 'delete-pengambilan',
            'lihat-parameter', 'tambah-parameter', 'edit-parameter', 'delete-parameter',
            'lihat-kategori', 'tambah-kategori', 'edit-kategori', 'delete-kategori',
            'lihat-jenis_sampel', 'tambah-jenis_sampel', 'edit-jenis_sampel', 'delete-jenis_sampel',
            'detail-pelanggan', 'lihat-hasil_uji', 'detail-hasil_uji'
        ];
        
        $adminPermissionId = Permission::whereIn('name', $adminPermissions)->pluck('id')->toArray();
        $role->syncPermissions($adminPermissionId);
    }
}
