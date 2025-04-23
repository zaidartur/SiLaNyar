<?php

namespace Database\Seeders;

use App\Models\pegawai;
use App\Models\roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = pegawai::create([
            'nama' => 'Super Admin',
            'email' => 'selainsuperadmin@gmail.com',
            'password' => bcrypt('namanyasilanyar'),
            'jabatan' => 'superadmin',
            'jenis_kelamin' => 'laki-laki',
            'no_telepon' => '+6285741358179',
        ]);

        $role = roles::firstOrCreate(['name' => 'superadmin'], ['guard_name' => 'pegawai']);

        $superadmin->assignRole('superadmin');

        $role->syncPermissions([1,2]);
    }
}
