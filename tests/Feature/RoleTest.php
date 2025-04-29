<?php

namespace Tests\Feature;

use App\Models\roles;
use App\Models\permissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use App\Models\pegawai;

class RoleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $permission;
    private $pegawai;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bypass Vite manifest requirement
        $this->withoutVite();
        
        // Buat pegawai dan berikan permission
        $this->pegawai = pegawai::factory()->create();
        $this->permission = permissions::factory()->create([
            'name' => 'kelola-role',
            'guard_name' => 'pegawai'
        ]);
        
        // Buat role dengan permission kelola-role
        $role = roles::create([
            'name' => 'admin',
            'guard_name' => 'pegawai'  // Tambahkan guard_name
        ]);
        $role->givePermissionTo('kelola-role');
        
        // Berikan role ke pegawai
        $this->pegawai->assignRole('admin');
    }

    public function test_can_display_role_list(): void
    {
        // Create roles first
        roles::factory()->count(3)->create();
        
        // Set guard dan login
        $this->actingAs($this->pegawai, 'pegawai');

        // Test the route
        $response = $this->get('/superadmin/role');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('role')
            ->has('role')
        );
    }

    public function test_can_create_new_role(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');
        
        $permissions = permissions::factory()->count(2)->create();
        
        $roleData = [
            'name' => $this->faker->unique()->word(),
            'permissions' => $permissions->pluck('id')->toArray()
        ];

        $response = $this->post('/superadmin/role/store', $roleData);

        $response->assertRedirect(route('role.index'));
        $this->assertDatabaseHas('roles', [
            'name' => $roleData['name'],
            'guard_name' => 'pegawai'  // Tambahkan pengecekan guard_name
        ]);

        // Check if permissions were attached
        $role = roles::where('name', $roleData['name'])->first();
        $this->assertEquals(2, $role->permissions()->count()); // Ubah ke permissions()
    }

    public function test_can_show_edit_role_form(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        $role = roles::factory()->create([
            'guard_name' => 'pegawai'
        ]);

        $response = $this->get("/superadmin/role/edit/{$role->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('role.edit')
            ->has('roles.permissions') // Ubah dari 'permission' ke 'permissions'
            ->has('permissions')
        );
    }

    public function test_can_update_role(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        $role = roles::factory()->create([
            'guard_name' => 'pegawai'
        ]);

        $permissions = permissions::factory()->count(2)->create([
            'guard_name' => 'pegawai'
        ]);

        $updatedData = [
            'name' => 'Updated Role Name', // Pastikan menggunakan 'name' bukan 'nama'
            'permissions' => $permissions->pluck('id')->toArray()
        ];

        $response = $this->post("/superadmin/role/{$role->id}/edit", $updatedData);

        $response->assertRedirect('/superadmin/role');
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $updatedData['name'],
            'guard_name' => 'pegawai'
        ]);
        
        // Verifikasi permissions
        $this->assertEquals(2, $role->fresh()->permissions()->count());
    }

    public function test_can_delete_role(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        // Buat role dengan guard_name yang benar
        $role = roles::factory()->create([
            'guard_name' => 'pegawai'
        ]);

        $response = $this->delete("/superadmin/role/{$role->id}");

        $response->assertRedirect('/superadmin/role');
        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
            'guard_name' => 'pegawai'
        ]);
    }

    public function test_validates_required_fields_on_create(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        // Simulasikan session untuk test
        $this->withSession(['_token' => 'test-token']);
        
        $response = $this->from('/superadmin/role/create')  // Tambahkan previous URL
                        ->post('/superadmin/role/store', []); // Kirim request kosong

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.' // Tambahkan pesan error yang diharapkan
        ]);
        
        $response->assertRedirect(); // Pastikan ada redirect
    }

    public function test_role_name_must_be_unique(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        // Create existing role first
        $existingRole = roles::create([
            'name' => 'Test Role',
            'guard_name' => 'pegawai'
        ]);

        // Simulate session
        $this->withSession(['_token' => 'test-token']);

        // Attempt to create duplicate role
        $roleData = [
            'name' => 'Test Role',
            'permissions' => []
        ];

        // Send request with referrer
        $response = $this->from('/superadmin/role/create')
                        ->post('/superadmin/role/store', $roleData);

        // Assert validation error and redirect
        $response->assertSessionHasErrors(['name']);
        $response->assertRedirect('/superadmin/role/create');
    }

    public function test_can_assign_multiple_permissions_to_role(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        // Buat role dengan guard name
        $role = roles::create([
            'name' => 'Test Role',
            'guard_name' => 'pegawai'
        ]);

        // Buat permissions dengan guard name yang sama
        $permissions = permissions::factory()->count(3)->create([
            'guard_name' => 'pegawai'
        ]);

        // Gunakan syncPermissions dari Spatie
        $role->syncPermissions($permissions->pluck('id')->toArray());

        // Gunakan permissions() untuk mengecek relasi
        $this->assertEquals(3, $role->permissions->count());
    }

    public function test_validates_valid_permissions(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        // Simulasikan session
        $this->withSession(['_token' => 'test-token']);

        // Data dengan invalid permission ID
        $roleData = [
            'name' => 'Test Role',
            'permissions' => [999999] // Non-existent permission ID
        ];

        // Send request dengan previous URL
        $response = $this->from('/superadmin/role/create')
                        ->post('/superadmin/role/store', $roleData);

        // Assert errors dan redirect
        $response->assertSessionHasErrors([
            'permissions.0' => 'The selected permissions.0 is invalid.'
        ]);
        $response->assertRedirect();
    }

    public function test_deleting_role_removes_permission_relationships(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        // Buat role dengan guard name
        $role = roles::create([
            'name' => 'Test Role',
            'guard_name' => 'pegawai'
        ]);

        // Buat permissions dengan guard name
        $permissions = permissions::factory()->count(2)->create([
            'guard_name' => 'pegawai'
        ]);

        // Gunakan syncPermissions untuk attach
        $role->syncPermissions($permissions->pluck('id')->toArray());

        // Verify permissions are attached
        $this->assertEquals(2, $role->permissions->count());

        // Delete role
        $role->delete();

        // Verify permissions relationship is removed
        $this->assertDatabaseMissing('role_has_permissions', ['role_id' => $role->id]);
    }

    public function test_can_show_role_details(): void
    {
        // Login sebagai pegawai
        $this->actingAs($this->pegawai, 'pegawai');

        // Buat role dengan guard name
        $role = roles::factory()->create([
            'guard_name' => 'pegawai'
        ]);

        // Buat permissions dengan guard name yang sama
        $permissions = permissions::factory()->count(2)->create([
            'guard_name' => 'pegawai'
        ]);

        // Gunakan syncPermissions untuk attach permissions
        $role->syncPermissions($permissions->pluck('id')->toArray());

        $response = $this->get("/superadmin/role/edit/{$role->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('role.edit')
            ->has('roles.permissions', 2)  // Ubah dari permission ke permissions
            ->has('permissions')
        );
    }
}
