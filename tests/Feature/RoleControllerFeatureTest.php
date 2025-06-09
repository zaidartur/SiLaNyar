<?php

namespace Tests\Feature;

use App\Models\Permissions;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoleControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected Roles $superAdminRole;

    public function setUp(): void
    {
        parent::setUp();

        // Konfigurasi Vite untuk testing
        config(['app.asset_url' => null]);

        // Buat role superadmin dengan permission
        $this->superAdminRole = Roles::firstOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web'],
            ['kode_role' => 'RL-000']
        );

        // Buat permission kelola role
        $kelolaRole = Permissions::firstOrCreate([
            'name' => 'kelola role',
            'guard_name' => 'web'
        ]);

        $this->superAdminRole->givePermissionTo($kelolaRole);

        // Buat user superadmin
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole($this->superAdminRole);
    }

    public function test_index_menampilkan_halaman_role_dengan_data_lengkap()
    {
        // Buat sample role dengan permissions
        $permissions = Permissions::factory()->count(3)->create();
        $roles = Roles::factory()->count(2)->create();
        
        // Berikan permission ke role
        $roles[0]->givePermissionTo($permissions[0], $permissions[1]);
        $roles[1]->givePermissionTo($permissions[2]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('superadmin.role.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/role/Index')
                ->has('role')
                ->has('role', 3) // 2 yang dibuat + 1 dari setUp (superadmin)
                ->has('role.0', fn (Assert $role) => $role
                    ->where('name', 'superadmin')
                    ->where('guard_name', 'web')
                    ->has('id')
                    ->has('kode_role')
                    ->has('permissions')
                    ->has('permissions', 1) // permission kelola role
                    ->has('permissions.0', fn (Assert $permission) => $permission
                        ->where('name', 'kelola role')
                        ->has('id')
                        ->has('kode_permission')
                        ->etc()
                    )
                    ->etc()
                )
                ->has('role.1', fn (Assert $role) => $role
                    ->where('id', $roles[0]->id)
                    ->where('name', $roles[0]->name)
                    ->where('guard_name', 'web')
                    ->has('kode_role')
                    ->has('permissions', 2) // 2 permission yang diberikan
                    ->etc()
                )
            );
    }

    public function test_index_menampilkan_role_tanpa_permissions()
    {
        // Buat role tanpa permissions
        $roleWithoutPermissions = Roles::factory()->create([
            'name' => 'basic role',
            'guard_name' => 'web'
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('superadmin.role.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/role/Index')
                ->has('role', 2) // superadmin + basic role
                ->has('role.1', fn (Assert $role) => $role
                    ->where('name', 'basic role')
                    ->has('permissions', 0) // tidak ada permissions
                    ->etc()
                )
            );
    }

    public function test_create_menampilkan_halaman_tambah_role_dengan_permissions()
    {
        $permissions = Permissions::factory()->count(5)->create();

        $response = $this->actingAs($this->superAdmin)
            ->get('/superadmin/role/create');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/role/Tambah')
                ->has('permission')
                ->has('permission', 6) // 5 yang dibuat + 1 dari setUp
                ->has('permission.0', fn (Assert $permission) => $permission
                    ->where('name', 'kelola role')
                    ->has('id')
                    ->has('kode_permission')
                    ->etc()
                )
                ->has('permission.1', fn (Assert $permission) => $permission
                    ->where('id', $permissions[0]->id)
                    ->where('name', $permissions[0]->name)
                    ->has('kode_permission')
                    ->etc()
                )
            );
    }

    public function test_store_berhasil_membuat_role_baru()
    {
        $permissions = Permissions::factory()->count(3)->create();
        
        $roleData = [
            'name' => 'New Test Role',
            'permissions' => [$permissions[0]->id, $permissions[1]->id],
            'dashboard_view' => 'admin.dashboard'
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post('/superadmin/role/store', $roleData);

        $response->assertRedirect(route('superadmin.role.index'))
            ->assertSessionHas('message', 'Role Berhasil Ditambahkan!');

        $this->assertDatabaseHas('roles', [
            'name' => 'New Test Role',
            'guard_name' => 'web',
            'dashboard_view' => 'admin.dashboard'
        ]);

        $createdRole = Roles::where('name', 'New Test Role')->first();
        $this->assertTrue($createdRole->hasPermissionTo($permissions[0]));
        $this->assertTrue($createdRole->hasPermissionTo($permissions[1]));
        $this->assertFalse($createdRole->hasPermissionTo($permissions[2]));
    }

    public function test_store_berhasil_membuat_role_tanpa_permissions()
    {
        $roleData = [
            'name' => 'Role Without Permissions',
            'dashboard_view' => null
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post('/superadmin/role/store', $roleData);

        $response->assertRedirect(route('superadmin.role.index'))
            ->assertSessionHas('message', 'Role Berhasil Ditambahkan!');

        $this->assertDatabaseHas('roles', [
            'name' => 'Role Without Permissions',
            'guard_name' => 'web',
            'dashboard_view' => null
        ]);
    }

    public function test_store_gagal_dengan_nama_yang_sudah_ada()
    {
        $roleData = [
            'name' => 'superadmin', // Sama dengan role yang sudah ada
            'permissions' => []
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post('/superadmin/role/store', $roleData);

        $response->assertRedirect()
            ->assertSessionHasErrors(['name' => 'Nama Sudah Dipakai.'])
            ->assertSessionHasInput('name');
    }

    public function test_store_gagal_dengan_validasi_yang_tidak_valid()
    {
        $roleData = [
            'name' => 'Valid Role Name',
            'permissions' => [99999] // ID permission yang tidak ada
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post('/superadmin/role/store', $roleData);

        $response->assertRedirect()
            ->assertSessionHasErrors(['permissions.0'])
            ->assertSessionHasInput('name');
    }

    public function test_edit_menampilkan_halaman_edit_dengan_data_role()
    {
        $permissions = Permissions::factory()->count(4)->create();
        $role = Roles::factory()->create([
            'name' => 'Test Edit Role',
            'dashboard_view' => 'test.dashboard'
        ]);
        $role->givePermissionTo($permissions[0], $permissions[2]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('superadmin.role.edit', $role));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/role/Edit')
                ->has('roles', fn (Assert $roleData) => $roleData
                    ->where('id', $role->id)
                    ->where('name', 'Test Edit Role')
                    ->where('dashboard_view', 'test.dashboard')
                    ->has('permissions', 2) // 2 permission yang diberikan
                    ->has('permissions.0', fn (Assert $permission) => $permission
                        ->where('id', $permissions[0]->id)
                        ->has('name')
                        ->has('kode_permission')
                        ->etc()
                    )
                    ->etc()
                )
                ->has('permissions') // Semua permission yang tersedia
                ->has('permissions', 5) // 4 yang dibuat + 1 dari setUp
            );
    }

    public function test_update_berhasil_mengupdate_role()
    {
        $permissions = Permissions::factory()->count(3)->create();
        $role = Roles::factory()->create(['name' => 'Old Role Name']);
        $role->givePermissionTo($permissions[0]);

        $updateData = [
            'name' => 'Updated Role Name',
            'permissions' => [$permissions[1]->id, $permissions[2]->id],
            'dashboard_view' => 'updated.dashboard'
        ];

        $response = $this->actingAs($this->superAdmin)
            ->put("/superadmin/role/{$role->id}/edit", $updateData);

        $response->assertRedirect(route('superadmin.role.index'))
            ->assertSessionHas('message', 'Role Berhasil Diupdate!');

        $role->refresh();
        $this->assertEquals('Updated Role Name', $role->name);
        $this->assertEquals('updated.dashboard', $role->dashboard_view);
        $this->assertFalse($role->hasPermissionTo($permissions[0]));
        $this->assertTrue($role->hasPermissionTo($permissions[1]));
        $this->assertTrue($role->hasPermissionTo($permissions[2]));
    }

    public function test_destroy_berhasil_menghapus_role()
    {
        $role = Roles::factory()->create(['name' => 'Role to Delete']);

        $response = $this->actingAs($this->superAdmin)
            ->delete("/superadmin/role/{$role->id}");

        $response->assertRedirect(route('superadmin.role.index'))
            ->assertSessionHas('message', 'Role Berhasil Dihapus!');

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id
        ]);
    }

    public function test_destroy_mengembalikan_404_untuk_role_tidak_ditemukan()
    {
        $nonExistentId = 99999;

        $response = $this->actingAs($this->superAdmin)
            ->delete("/superadmin/role/{$nonExistentId}");

        $response->assertStatus(404);
    }

    public function test_semua_method_memerlukan_autentikasi()
    {
        $role = Roles::factory()->create();

        // Test index
        $response = $this->get(route('superadmin.role.index'));
        $response->assertRedirect()->assertRedirectContains('/sso/login');

        // Test create
        $response = $this->get('/superadmin/role/create');
        $response->assertRedirect()->assertRedirectContains('/sso/login');

        // Test store
        $response = $this->post('/superadmin/role/store', ['name' => 'Test']);
        $response->assertRedirect()->assertRedirectContains('/sso/login');

        // Test edit
        $response = $this->get(route('superadmin.role.edit', $role));
        $response->assertRedirect()->assertRedirectContains('/sso/login');

        // Test update
        $response = $this->put("/superadmin/role/{$role->id}/edit", ['name' => 'Test']);
        $response->assertRedirect()->assertRedirectContains('/sso/login');

        // Test destroy
        $response = $this->delete("/superadmin/role/{$role->id}");
        $response->assertRedirect()->assertRedirectContains('/sso/login');
    }

    public function test_semua_method_memerlukan_permission_kelola_role()
    {
        $userWithoutPermission = User::factory()->create();
        $basicRole = Roles::firstOrCreate(
            ['name' => 'basic', 'guard_name' => 'web'],
            ['kode_role' => 'RL-999']
        );
        $userWithoutPermission->assignRole($basicRole);
        $role = Roles::factory()->create();

        // Test index
        $response = $this->actingAs($userWithoutPermission)
            ->get(route('superadmin.role.index'));
        $response->assertStatus(403);

        // Test create
        $response = $this->actingAs($userWithoutPermission)
            ->get('/superadmin/role/create');
        $response->assertStatus(403);

        // Test store
        $response = $this->actingAs($userWithoutPermission)
            ->post('/superadmin/role/store', ['name' => 'Test']);
        $response->assertStatus(403);

        // Test edit
        $response = $this->actingAs($userWithoutPermission)
            ->get(route('superadmin.role.edit', $role));
        $response->assertStatus(403);

        // Test update
        $response = $this->actingAs($userWithoutPermission)
            ->put("/superadmin/role/{$role->id}/edit", ['name' => 'Test']);
        $response->assertStatus(403);

        // Test destroy
        $response = $this->actingAs($userWithoutPermission)
            ->delete("/superadmin/role/{$role->id}");
        $response->assertStatus(403);
    }

    public function test_role_auto_generates_kode_role()
    {
        $role = Roles::factory()->create([
            'name' => 'auto kode test',
            'guard_name' => 'web'
        ]);

        $this->assertNotNull($role->kode_role);
        $this->assertStringStartsWith('RL-', $role->kode_role);
        $this->assertEquals(6, strlen($role->kode_role)); // format RL-XXX
    }

    public function test_kode_role_increments_correctly()
    {
        // Buat role pertama
        $role1 = Roles::factory()->create([
            'name' => 'first role',
            'guard_name' => 'web'
        ]);

        // Buat role kedua
        $role2 = Roles::factory()->create([
            'name' => 'second role', 
            'guard_name' => 'web'
        ]);

        // Ekstrak bagian angka
        $kode1Number = (int)substr($role1->kode_role, -3);
        $kode2Number = (int)substr($role2->kode_role, -3);

        $this->assertEquals($kode1Number + 1, $kode2Number);
    }
}
