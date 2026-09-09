<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'superadmin', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/SuperAdmin'],
            ['name' => 'pelanggan', 'guard_name' => 'web', 'dashboard_view' => 'customer/dashboard/Index'],
            ['name' => 'staf_administrator', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Admin'],
            ['name' => 'pengendali_teknis', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/PengendaliTeknis'],
            ['name' => 'ppcu', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/PPCU'],
            ['name' => 'penyelia', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Penyelia'],
            ['name' => 'analis', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Teknisi'],
            ['name' => 'kepala_lab', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/KepalaLab'],
            ['name' => 'kepala_dinas', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/KepalaDinas'],
            // Role legacy untuk kompatibilitas pengujian lama
            ['name' => 'customer', 'guard_name' => 'web', 'dashboard_view' => 'customer/dashboard/Index'],
            ['name' => 'admin', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Admin'],
            ['name' => 'teknisi', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Teknisi'],
        ];

        foreach ($roles as $role) {
            Roles::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                ['dashboard_view' => $role['dashboard_view']]
            );
        }
    }
}
