<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pegawai;
use App\Models\Roles;
use App\Models\Permissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class SuperadminRoleTest extends TestCase
{
    use RefreshDatabase;

    protected $superadmin;
    protected $permissions;

    public function setUp(): void
    {
        parent::setUp();

        // Buat permissions sekali saja menggunakan upsert
        $permissionNames = ['kelola-permission', 'kelola-role'];
        $permissionsData = collect($permissionNames)->map(function ($name) {
            return [
                'name' => $name,
                'guard_name' => 'pegawai',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // Gunakan upsert untuk menghindari duplikasi
        Permissions::upsert(
            $permissionsData,
            ['name', 'guard_name'],
            ['updated_at']
        );

        // Ambil permissions yang sudah dibuat
        $this->permissions = Permissions::whereIn('name', $permissionNames)->get();
        
        // Buat superadmin
        $this->superadmin = Pegawai::factory()->create([
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now(),
            'jabatan' => 'Super Admin'
        ]);

        $this->superadmin->givePermissionTo($this->permissions);
    }

    // Untuk test yang membutuhkan permissions baru, gunakan method ini
    protected function createUniquePermission()
    {
        return Permissions::factory()->create([
            'name' => 'test-permission-' . uniqid(),
        ]);
    }

    public function test_superadmin_bisa_melihat_daftar_role()
    {
        $response = $this->actingAs($this->superadmin, 'pegawai')->get('/superadmin/role');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/role/index')
                ->has('role'));
    }

    public function test_superadmin_bisa_membuat_role_baru()
    {
        $roleData = [
            'name' => 'Analis Lab',
            'permissions' => $this->permissions->pluck('id')->toArray(),
        ];

        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->from('/superadmin/role')
            ->post('/superadmin/role/store', $roleData);

        $response->assertRedirect('/superadmin/role');

        $this->assertDatabaseHas('roles', [
            'name' => 'Analis Lab',
            'guard_name' => 'pegawai',
        ]);

        $role = Roles::where('name', 'Analis Lab')->first();
        $this->assertEquals($this->permissions->count(), $role->permissions->count());
    }

    public function test_superadmin_bisa_mengupdate_role()
    {
        $role = Roles::factory()->create();
        $permission = Permissions::factory()->create();

        $updateData = [
            'name' => 'Role Updated',
            'permissions' => [$permission->id],
        ];

        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post("/superadmin/role/{$role->id}/edit", $updateData);

        $response->assertRedirect(route('superadmin.role.index'));

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Role Updated',
        ]);
    }

    public function test_superadmin_bisa_menghapus_role()
    {
        $role = Roles::factory()->create();

        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->delete("/superadmin/role/{$role->id}");

        $response->assertRedirect(route('superadmin.role.index'));

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    public function test_nama_role_harus_unik()
    {
        Roles::create([
            'name' => 'Admin Lab',
            'guard_name' => 'pegawai',
        ]);

        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post('/superadmin/role/store', [
                'name' => 'Admin Lab',
                'permissions' => [],
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_permission_harus_valid()
    {
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post('/superadmin/role/store', [
                'name' => 'Role Baru',
                'permissions' => [999], // ID permission tidak valid
            ]);

        $response->assertSessionHasErrors('permissions.*');
    }

    public function test_role_bisa_diassign_multiple_permission()
    {
        $newPermissions = collect(range(1, 3))->map(function () {
            return $this->createUniquePermission();
        });
        
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post('/superadmin/role/store', [
                'name' => 'Multi Permission Role',
                'permissions' => $newPermissions->pluck('id')->toArray()
            ]);

        $role = Roles::where('name', 'Multi Permission Role')->first();
        $this->assertEquals(3, $role->permissions->count());
    }
}
