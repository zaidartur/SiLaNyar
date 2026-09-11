<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_inertia_requests_shares_role_and_is_pegawai_properly()
    {
        $role = \App\Models\Roles::firstOrCreate(
            ['name' => 'staf_administrator', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002', 'dashboard_view' => 'dashboard/Admin']
        );
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->component('Dashboard')
                    ->has('auth.user.roles', 1)
                    ->where('auth.is_pegawai', true)
                    ->where('auth.is_customer', false)
                    ->where('auth.roles.0', 'staf_administrator');
            });
    }

    public function test_handle_inertia_requests_shares_is_customer_for_pelanggan()
    {
        $role = \App\Models\Roles::firstOrCreate(
            ['name' => 'pelanggan', 'guard_name' => 'web'],
            ['kode_role' => 'RL-003', 'dashboard_view' => 'customer/dashboard/Index']
        );
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->component('Dashboard')
                    ->has('auth.user.roles', 1)
                    ->where('auth.is_pegawai', false)
                    ->where('auth.is_customer', true)
                    ->where('auth.roles.0', 'pelanggan');
            });
    }
}
