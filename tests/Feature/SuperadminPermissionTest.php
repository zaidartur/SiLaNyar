<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pegawai;
use App\Models\Permissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class SuperadminPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $superadmin;

    public function setUp(): void
    {
        parent::setUp();

        // Buat permission untuk superadmin
        $permissions = collect(['kelola-permission', 'kelola-role'])->map(function ($name) {
            return Permissions::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'pegawai']
            );
        });

        // Buat superadmin
        $this->superadmin = Pegawai::factory()->create([
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now(),
            'jabatan' => 'Super Admin'
        ]);

        // Berikan permission ke superadmin
        $this->superadmin->givePermissionTo($permissions);
    }

    public function test_superadmin_bisa_melihat_daftar_permission()
    {
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->get('/superadmin/permission');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/permission/index')
                ->has('permission')
            );
    }

    public function test_superadmin_bisa_membuat_permission_baru()
    {
        $permissionData = [
            'name' => 'lihat-dashboard',
            'guard_name' => 'pegawai'
        ];

        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post('/superadmin/permission/store', $permissionData);

        $response->assertRedirect(route('superadmin.permission.index'));
        
        $this->assertDatabaseHas('permissions', $permissionData);
    }

    public function test_superadmin_bisa_mengupdate_permission()
    {
        $permission = Permissions::factory()->create([
            'name' => 'test-permission'
        ]);

        $updateData = [
            'name' => 'permission-updated',
            'guard_name' => 'pegawai'
        ];

        // Ubah route sesuai web.php
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->put("/superadmin/permission/{$permission->id}/edit", $updateData);

        $response->assertRedirect(route('superadmin.permission.index'));
        
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'permission-updated'
        ]);
    }

    public function test_superadmin_bisa_menghapus_permission()
    {
        $permission = Permissions::factory()->create();

        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->delete("/superadmin/permission/{$permission->id}");

        $response->assertRedirect(route('superadmin.permission.index'));
        
        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id
        ]);
    }

    public function test_nama_permission_harus_diisi()
    {
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post('/superadmin/permission/store', [
                'name' => '',
                'guard_name' => 'pegawai'
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_nama_permission_maksimal_255_karakter()
    {
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post('/superadmin/permission/store', [
                'name' => str_repeat('a', 256),
                'guard_name' => 'pegawai'
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_nama_permission_harus_string()
    {
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->post('/superadmin/permission/store', [
                'name' => 123,
                'guard_name' => 'pegawai'
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_superadmin_bisa_mengakses_halaman_tambah_permission()
    {
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->get('/superadmin/permission/create');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/permission/tambah')
            );
    }

    public function test_superadmin_bisa_mengakses_halaman_edit_permission()
    {
        $permission = Permissions::factory()->create();

        // Ubah route sesuai web.php
        $response = $this->actingAs($this->superadmin, 'pegawai')
            ->get("/superadmin/permission/edit/{$permission->id}");

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/permission/edit')
                ->has('permission')
            );
    }
}
