<?php

namespace Tests\Feature;

use App\Models\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected Role $superAdminRole;

    public function setUp(): void
    {
        parent::setUp();

        // Konfigurasi Vite untuk testing
        config(['app.asset_url' => null]);

        // Buat role superadmin dengan permission
        $this->superAdminRole = Role::firstOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web'],
            ['kode_role' => 'RL-000']
        );

        // Buat permission kelola permission
        $kelolaPermission = Permissions::firstOrCreate([
            'name' => 'kelola permission',
            'guard_name' => 'web'
        ]);

        $this->superAdminRole->givePermissionTo($kelolaPermission);

        // Buat user superadmin
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole($this->superAdminRole);
    }

    public function test_index_menampilkan_halaman_permission_dengan_data_lengkap()
    {
        // Buat sample permissions
        $permissions = Permissions::factory()->count(3)->create();

        $response = $this->actingAs($this->superAdmin)
            ->get(route('superadmin.permission.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/permission/Index')
                ->has('permission')
                ->has('permission', 4) // 3 yang dibuat + 1 dari setUp (kelola permission)
                ->has('permission.0', fn (Assert $permission) => $permission
                    ->where('name', 'kelola permission')
                    ->where('guard_name', 'web')
                    ->has('id')
                    ->has('kode_permission')
                    ->etc()
                )
                ->has('permission.1', fn (Assert $permission) => $permission
                    ->where('id', $permissions[0]->id)
                    ->where('name', $permissions[0]->name)
                    ->where('guard_name', 'web')
                    ->has('kode_permission')
                    ->etc()
                )
            );
    }

    public function test_index_menampilkan_array_kosong_ketika_tidak_ada_permission()
    {
        // Hapus semua permission kecuali yang dibuat di setUp
        Permissions::where('name', '!=', 'kelola permission')->delete();

        $response = $this->actingAs($this->superAdmin)
            ->get(route('superadmin.permission.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/permission/Index')
                ->has('permission', 1) // Hanya yang dari setUp
                ->has('permission.0', fn (Assert $permission) => $permission
                    ->where('name', 'kelola permission')
                    ->etc()
                )
            );
    }

    public function test_index_memerlukan_autentikasi_dan_permission()
    {
        $response = $this->get(route('superadmin.permission.index'));

        $response->assertRedirect()
            ->assertRedirectContains('/sso/login');
    }

    public function test_index_memblokir_user_tanpa_permission_kelola_permission()
    {
        $userWithoutPermission = User::factory()->create();
        $basicRole = Role::firstOrCreate(
            ['name' => 'basic', 'guard_name' => 'web'],
            ['kode_role' => 'RL-999']
        );
        $userWithoutPermission->assignRole($basicRole);

        $response = $this->actingAs($userWithoutPermission)
            ->get(route('superadmin.permission.index'));

        $response->assertStatus(403);
    }

    public function test_destroy_mengembalikan_404_untuk_permission_tidak_ditemukan()
    {
        $nonExistentId = 99999;

        $response = $this->actingAs($this->superAdmin)
            ->delete("/superadmin/permission/{$nonExistentId}");

        $response->assertStatus(404);
    }

    public function test_permission_auto_generates_kode_permission()
    {
        $permission = Permissions::factory()->create([
            'name' => 'auto kode test',
            'guard_name' => 'web'
        ]);

        $this->assertNotNull($permission->kode_permission);
        $this->assertStringStartsWith('PS-', $permission->kode_permission);
        $this->assertEquals(6, strlen($permission->kode_permission)); // format PS-XXX
    }

    public function test_kode_permission_increments_correctly()
    {
        // Buat permission pertama
        $permission1 = Permissions::factory()->create([
            'name' => 'first permission',
            'guard_name' => 'web'
        ]);

        // Buat permission kedua
        $permission2 = Permissions::factory()->create([
            'name' => 'second permission', 
            'guard_name' => 'web'
        ]);

        // Ekstrak bagian angka
        $kode1Number = (int)substr($permission1->kode_permission, -3);
        $kode2Number = (int)substr($permission2->kode_permission, -3);

        $this->assertEquals($kode1Number + 1, $kode2Number);
    }
}
