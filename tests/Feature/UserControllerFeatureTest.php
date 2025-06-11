<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected Role $superAdminRole;
    protected Role $customerRole;

    public function setUp(): void
    {
        parent::setUp();

        // Konfigurasi Vite untuk testing
        config(['app.asset_url' => null]);

        // Buat role superadmin dan customer
        $this->superAdminRole = Role::firstOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web'],
            ['kode_role' => 'RL-000']
        );

        $this->customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        // Buat permission kelola user
        $kelolaUserPermission = \App\Models\Permissions::firstOrCreate([
            'name' => 'kelola user',
            'guard_name' => 'web'
        ]);

        $this->superAdminRole->givePermissionTo($kelolaUserPermission);

        // Buat user superadmin
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole($this->superAdminRole);
    }

    public function test_index_menampilkan_halaman_user_dengan_data_lengkap()
    {
        // Buat sample users dengan role
        $users = User::factory()->count(3)->create();
        $users[0]->assignRole($this->customerRole);
        $users[1]->assignRole($this->customerRole);
        $users[2]->assignRole($this->superAdminRole);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('pegawai.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/user/Index')
                ->has('users')
                ->has('users', 4) // 3 yang dibuat + 1 superAdmin dari setUp
                ->has('roles')
                ->has('roles', 2) // superadmin dan customer role
                ->has('filters')
                ->has('filters.search')
                ->has('users.0', fn (Assert $user) => $user
                    ->where('id', $this->superAdmin->id)
                    ->where('nama', $this->superAdmin->nama)
                    ->where('email', $this->superAdmin->email)
                    ->has('roles')
                    ->has('roles.0', fn (Assert $role) => $role
                        ->where('name', 'superadmin')
                        ->where('guard_name', 'web')
                        ->etc()
                    )
                    ->etc()
                )
                ->has('roles.0', fn (Assert $role) => $role
                    ->where('name', 'superadmin')
                    ->where('guard_name', 'web')
                    ->has('kode_role')
                    ->etc()
                )
            );
    }

    public function test_index_menampilkan_array_kosong_ketika_tidak_ada_user()
    {
        // Hapus semua user kecuali superAdmin
        User::where('id', '!=', $this->superAdmin->id)->delete();

        $response = $this->actingAs($this->superAdmin)
            ->get(route('pegawai.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/user/Index')
                ->has('users', 1) // Hanya superAdmin
                ->has('roles', 2) // roles tetap ada
                ->has('filters')
                ->has('users.0', fn (Assert $user) => $user
                    ->where('id', $this->superAdmin->id)
                    ->etc()
                )
            );
    }

    public function test_index_melakukan_pencarian_berdasarkan_nama()
    {
        // Buat user dengan nama spesifik
        $targetUser = User::factory()->create(['nama' => 'John Doe Testing']);
        $targetUser->assignRole($this->customerRole);
        
        // Buat user lain yang tidak akan muncul di pencarian
        $otherUser = User::factory()->create(['nama' => 'Jane Smith']);
        $otherUser->assignRole($this->customerRole);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('pegawai.index', ['search' => 'John Doe']));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/user/Index')
                ->has('users', 1) // Hanya user yang match pencarian
                ->has('filters', fn (Assert $filters) => $filters
                    ->where('search', 'John Doe')
                )
                ->has('users.0', fn (Assert $user) => $user
                    ->where('nama', 'John Doe Testing')
                    ->where('id', $targetUser->id)
                    ->etc()
                )
            );
    }

    public function test_index_melakukan_pencarian_berdasarkan_email()
    {
        // Buat user dengan email spesifik
        $targetUser = User::factory()->create(['email' => 'test@example.com']);
        $targetUser->assignRole($this->customerRole);
        
        // Buat user lain
        $otherUser = User::factory()->create(['email' => 'other@example.com']);
        $otherUser->assignRole($this->customerRole);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('pegawai.index', ['search' => 'test@example']));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/user/Index')
                ->has('users', 1)
                ->has('users.0', fn (Assert $user) => $user
                    ->where('email', 'test@example.com')
                    ->where('id', $targetUser->id)
                    ->etc()
                )
            );
    }

    public function test_index_memerlukan_autentikasi_dan_permission()
    {
        $response = $this->get(route('pegawai.index'));

        $response->assertRedirect()
            ->assertRedirectContains('/sso/login');
    }

    public function test_index_memblokir_user_tanpa_permission_kelola_user()
    {
        $userWithoutPermission = User::factory()->create();
        $basicRole = Role::firstOrCreate(
            ['name' => 'basic', 'guard_name' => 'web'],
            ['kode_role' => 'RL-999']
        );
        $userWithoutPermission->assignRole($basicRole);

        $response = $this->actingAs($userWithoutPermission)
            ->get(route('pegawai.index'));

        $response->assertStatus(403);
    }

    public function test_sync_roles_berhasil_menyinkronkan_role_user()
    {
        $targetUser = User::factory()->create();
        $targetUser->assignRole($this->customerRole);

        // Pastikan user awalnya memiliki role customer
        $this->assertTrue($targetUser->hasRole('customer'));
        $this->assertFalse($targetUser->hasRole('superadmin'));

        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.users.syncRoles', $targetUser), [
                'roles' => ['superadmin']
            ]);

        $response->assertRedirect()
            ->assertSessionHas('message', 'User dan Role berhasil di sinkronkan!');

        // Refresh user dari database
        $targetUser->refresh();

        // Pastikan role berhasil diubah
        $this->assertTrue($targetUser->hasRole('superadmin'));
        $this->assertFalse($targetUser->hasRole('customer'));
    }

    public function test_sync_roles_mengembalikan_error_untuk_data_tidak_valid()
    {
        $targetUser = User::factory()->create();

        // Test dengan array kosong
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.users.syncRoles', $targetUser), [
                'roles' => []
            ]);

        $response->assertSessionHasErrors(['roles']);

        // Test dengan lebih dari satu role
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.users.syncRoles', $targetUser), [
                'roles' => ['superadmin', 'customer']
            ]);

        $response->assertSessionHasErrors(['roles']);

        // Test dengan role yang tidak ada
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.users.syncRoles', $targetUser), [
                'roles' => ['non_existent_role']
            ]);

        $response->assertSessionHasErrors(['roles.0']);
    }

    public function test_sync_roles_memerlukan_autentikasi_dan_permission()
    {
        $targetUser = User::factory()->create();

        // Test tanpa autentikasi
        $response = $this->post(route('superadmin.users.syncRoles', $targetUser), [
            'roles' => ['customer']
        ]);

        $response->assertRedirect()
            ->assertRedirectContains('/sso/login');

        // Test dengan user yang tidak memiliki permission
        $userWithoutPermission = User::factory()->create();
        $basicRole = Role::firstOrCreate(
            ['name' => 'basic', 'guard_name' => 'web'],
            ['kode_role' => 'RL-998']
        );
        $userWithoutPermission->assignRole($basicRole);

        $response = $this->actingAs($userWithoutPermission)
            ->post(route('superadmin.users.syncRoles', $targetUser), [
                'roles' => ['customer']
            ]);

        $response->assertStatus(403);
    }

    public function test_index_menampilkan_user_dengan_relasi_role_yang_benar()
    {
        $user = User::factory()->create();
        $user->assignRole($this->customerRole);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('pegawai.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('superadmin/user/Index')
                ->has('users')
                ->has('users.1', fn (Assert $userAssert) => $userAssert
                    ->where('id', $user->id)
                    ->has('roles')
                    ->has('roles.0', fn (Assert $role) => $role
                        ->where('name', 'customer')
                        ->where('guard_name', 'web')
                        ->has('kode_role')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_sync_roles_hanya_menerima_satu_role()
    {
        $targetUser = User::factory()->create();

        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.users.syncRoles', $targetUser), [
                'roles' => ['customer']
            ]);

        $response->assertRedirect()
            ->assertSessionHas('message', 'User dan Role berhasil di sinkronkan!');

        $targetUser->refresh();
        $this->assertCount(1, $targetUser->roles);
        $this->assertTrue($targetUser->hasRole('customer'));
    }
}
