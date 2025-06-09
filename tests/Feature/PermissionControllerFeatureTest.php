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

        // Configure Vite for testing
        config(['app.asset_url' => null]);

        // Create superadmin role with permission
        $this->superAdminRole = Role::firstOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web'],
            ['kode_role' => 'RL-000']
        );

        // Create kelola permission permission
        $kelolaPermission = Permissions::firstOrCreate([
            'name' => 'kelola permission',
            'guard_name' => 'web'
        ]);

        $this->superAdminRole->givePermissionTo($kelolaPermission);

        // Create superadmin user
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole($this->superAdminRole);
    }

    public function test_index_menampilkan_halaman_permission_dengan_data_lengkap()
    {
        // Create sample permissions
        $permissions = Permissions::factory()->count(3)->create();

        $response = $this->actingAs($this->superAdmin)
            ->get(route('superadmin.permission.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/permission/Index')
                ->has('permission')
                ->has('permission', 4) // 3 created + 1 from setUp (kelola permission)
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
        // Delete all permissions except the one created in setUp
        Permissions::where('name', '!=', 'kelola permission')->delete();

        $response = $this->actingAs($this->superAdmin)
            ->get(route('superadmin.permission.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/permission/Index')
                ->has('permission', 1) // Only the one from setUp
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

    public function test_destroy_menghapus_permission_yang_valid()
    {
        $permission = Permissions::factory()->create([
            'name' => 'test permission',
            'guard_name' => 'web'
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->delete("/superadmin/permission/{$permission->id}");

        $response->assertRedirect(route('superadmin.permission.index'))
            ->assertSessionHas('message', 'Permission Berhasil Dihapus!');

        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id
        ]);
    }

    public function test_destroy_mengembalikan_404_untuk_permission_tidak_ditemukan()
    {
        $nonExistentId = 99999;

        $response = $this->actingAs($this->superAdmin)
            ->delete("/superadmin/permission/{$nonExistentId}");

        $response->assertStatus(404);
    }

    public function test_destroy_memerlukan_autentikasi_dan_permission()
    {
        $permission = Permissions::factory()->create();

        // Test without authentication
        $response = $this->delete("/superadmin/permission/{$permission->id}");
        $response->assertRedirect()
            ->assertRedirectContains('/sso/login');

        // Test with user without permission
        $userWithoutPermission = User::factory()->create();
        $basicRole = Role::firstOrCreate(
            ['name' => 'basic', 'guard_name' => 'web'],
            ['kode_role' => 'RL-998']
        );
        $userWithoutPermission->assignRole($basicRole);

        $response = $this->actingAs($userWithoutPermission)
            ->delete("/superadmin/permission/{$permission->id}");

        $response->assertStatus(403);

        // Permission should still exist
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id
        ]);
    }

    public function test_permission_auto_generates_kode_permission()
    {
        $permission = Permissions::factory()->create([
            'name' => 'auto kode test',
            'guard_name' => 'web'
        ]);

        $this->assertNotNull($permission->kode_permission);
        $this->assertStringStartsWith('PS-', $permission->kode_permission);
        $this->assertEquals(6, strlen($permission->kode_permission)); // PS-XXX format
    }

    public function test_kode_permission_increments_correctly()
    {
        // Create first permission
        $permission1 = Permissions::factory()->create([
            'name' => 'first permission',
            'guard_name' => 'web'
        ]);

        // Create second permission
        $permission2 = Permissions::factory()->create([
            'name' => 'second permission', 
            'guard_name' => 'web'
        ]);

        // Extract numeric parts
        $kode1Number = (int)substr($permission1->kode_permission, -3);
        $kode2Number = (int)substr($permission2->kode_permission, -3);

        $this->assertEquals($kode1Number + 1, $kode2Number);
    }
}
