<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Roles;
use App\Models\Permissions;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Rename existing legacy roles jika sudah ada di database
        $customerRole = Roles::where('name', 'customer')->first();
        if ($customerRole) {
            $customerRole->update([
                'name' => 'pelanggan',
                'dashboard_view' => 'customer/dashboard/Index',
            ]);
        }

        $adminRole = Roles::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->update([
                'name' => 'staf_administrator',
                'dashboard_view' => 'dashboard/Admin',
            ]);
        }

        $teknisiRole = Roles::where('name', 'teknisi')->first();
        if ($teknisiRole) {
            $teknisiRole->update([
                'name' => 'analis',
                'dashboard_view' => 'dashboard/Teknisi',
            ]);
        }

        // 2. Jika database sudah memiliki role eksisting, tambahkan role DLH baru
        if (Roles::count() > 0) {
            $newRoles = [
                'pengendali_teknis' => 'dashboard/PengendaliTeknis',
                'ppcu'              => 'dashboard/PPCU',
                'penyelia'          => 'dashboard/Penyelia',
                'kepala_lab'        => 'dashboard/KepalaLab',
                'kepala_dinas'      => 'dashboard/KepalaDinas',
            ];

            foreach ($newRoles as $roleName => $dashboardView) {
                Roles::firstOrCreate(
                    ['name' => $roleName, 'guard_name' => 'web'],
                    ['dashboard_view' => $dashboardView]
                );
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $pelanggan = Roles::where('name', 'pelanggan')->first();
        if ($pelanggan) {
            $pelanggan->update(['name' => 'customer', 'dashboard_view' => 'customer/dashboard/Index']);
        }

        $stafAdmin = Roles::where('name', 'staf_administrator')->first();
        if ($stafAdmin) {
            $stafAdmin->update(['name' => 'admin', 'dashboard_view' => 'dashboard/Admin']);
        }

        $analis = Roles::where('name', 'analis')->first();
        if ($analis) {
            $analis->update(['name' => 'teknisi', 'dashboard_view' => 'dashboard/Teknisi']);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
