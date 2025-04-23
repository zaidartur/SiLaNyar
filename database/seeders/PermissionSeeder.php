<?php

namespace Database\Seeders;

use App\Models\permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = [
            'kelola-permission',
            'kelola-role'
        ];

        foreach ($permission as $per)
        {
            permissions::firstOrCreate(['name' => $per], ['guard_name' => 'pegawai']);
        }
    }
}
