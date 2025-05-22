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
        Roles::create(['name' => 'customer', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Customer']);
        Roles::create(['name' => 'superadmin', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/SuperAdmin']);
        Roles::create(['name' => 'teknisi', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Teknisi']);
        Roles::create(['name' => 'admin', 'guard_name' => 'web', 'dashboard_view' => 'dashboard/Admin']);
    }
}
