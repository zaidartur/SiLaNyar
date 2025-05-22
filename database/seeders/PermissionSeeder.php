<?php

namespace Database\Seeders;

use App\Models\Permissions;
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
            'kelola_permission',
            'kelola_role',
            'kelola_user',
            'kelola_instansi',
            'kelola_pembayaran',
            'kelola_pengujian',
            'kelola_jenis_cairan',
            'kelola_kategori',
            'kelola_parameter',
            'kelola_aduan',
        ];

        foreach ($permission as $per)
        {
            Permissions::firstOrCreate(['name' => $per], ['guard_name' => 'web']);
        }
    }
}
