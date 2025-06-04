<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Roles;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_role_dibuat_otomatis_saat_membuat_role()
    {
        $role = Roles::create(['name' => 'Test Role']);
        
        $this->assertNotNull($role->kode_role);
        $this->assertStringStartsWith('RL-', $role->kode_role);
        $this->assertEquals('RL-001', $role->kode_role);
    }

    #[Test]
    public function memastikan_kode_role_bertambah_secara_berurutan()
    {
        $role1 = Roles::create(['name' => 'Role 1']);
        $role2 = Roles::create(['name' => 'Role 2']);
        
        $this->assertEquals('RL-001', $role1->kode_role);
        $this->assertEquals('RL-002', $role2->kode_role);
    }

    #[Test]
    public function memastikan_guard_name_default_adalah_web()
    {
        $role = new Roles();
        $this->assertEquals('web', $role->guard_name);
        
        $role = Roles::create(['name' => 'Test Role']);
        $this->assertEquals('web', $role->guard_name);
    }

    #[Test]
    public function memastikan_atribut_dapat_diisi()
    {
        $attributes = [
            'name' => 'Custom Role',
            'guard_name' => 'api',
            'dashboard_view' => 'custom.dashboard'
        ];

        $role = Roles::create($attributes);

        $this->assertEquals($attributes['name'], $role->name);
        $this->assertEquals($attributes['guard_name'], $role->guard_name);
        $this->assertEquals($attributes['dashboard_view'], $role->dashboard_view);
    }

    #[Test]
    public function memastikan_factory_membuat_role_yang_valid()
    {
        $role = Roles::factory()->create();

        $this->assertNotNull($role->name);
        $this->assertNotNull($role->kode_role);
        $this->assertNotNull($role->dashboard_view);
        $this->assertEquals('web', $role->guard_name);
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $role->name,
            'dashboard_view' => $role->dashboard_view
        ]);
    }

    #[Test]
    public function memastikan_dashboard_view_bisa_kosong()
    {
        $role = Roles::create([
            'name' => 'Role Tanpa Dashboard'
        ]);

        $this->assertNull($role->dashboard_view);
    }

    #[Test]
    public function memastikan_kode_role_kustom_tidak_tertimpa()
    {
        $role = Roles::create([
            'name' => 'Role Kode Kustom',
            'kode_role' => 'RL-CUSTOM'
        ]);

        $this->assertEquals('RL-CUSTOM', $role->kode_role);
    }

    #[Test]
    public function memastikan_roles_factory_dengan_permission_dasar()
    {
        $permissions = [
            'lihat-pengujian',
            'lihat-hasil_uji',
            'lihat-pengajuan',
            'detail-pengajuan'
        ];

        foreach ($permissions as $key => $perm) {
            Permission::create([
                'name' => $perm,
                'kode_permission' => 'PM-' . str_pad($key + 1, 3, '0', STR_PAD_LEFT)
            ]);
        }

        $role = Roles::factory()->withBasicPermissions()->create();

        foreach ($permissions as $permission) {
            $this->assertTrue($role->hasPermissionTo($permission));
        }
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $role = Roles::factory()->create();
        
        $this->assertNotNull($role->created_at);
        $this->assertNotNull($role->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $role->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $role->updated_at);
    }
    
    #[Test]
    public function memastikan_bisa_update_dashboard_view()
    {
        $role = Roles::factory()->create([
            'dashboard_view' => 'old.dashboard'
        ]);
        
        $role->dashboard_view = 'new.dashboard';
        $role->save();
        
        $this->assertEquals('new.dashboard', $role->fresh()->dashboard_view);
    }

    #[Test]
    public function memastikan_role_bisa_diassign_ke_multiple_users()
    {
        $role = Roles::factory()->create();
        $users = \App\Models\User::factory()->count(3)->create();
        
        foreach ($users as $user) {
            $user->assignRole($role);
            $this->assertTrue($user->hasRole($role));
        }
        
        $this->assertEquals(3, $role->users()->count());
    }

    #[Test]
    public function memastikan_format_kode_role_valid()
    {
        $role = Roles::factory()->create();
        
        $this->assertMatchesRegularExpression('/^RL-\d{3}$/', $role->kode_role);
    }
}
