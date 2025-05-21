<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Roles;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_dapat_dibuat_dengan_kode_role_otomatis()
    {
        $role = Roles::create(['name' => 'Admin Test']);
        
        $this->assertNotNull($role->kode_role);
        $this->assertStringStartsWith('RL-', $role->kode_role);
        $this->assertEquals('RL-001', $role->kode_role);
    }

    public function test_roles_factory_dasar_berfungsi()
    {
        $role = Roles::factory()->create();

        $this->assertNotNull($role->name);
        $this->assertEquals('web', $role->guard_name);
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $role->name
        ]);
    }

    public function test_roles_factory_dengan_permission_dasar()
    {
        // Buat permission terlebih dahulu
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

    public function test_guard_name_default_adalah_web()
    {
        $role = new Roles();
        $this->assertEquals('web', $role->guard_name);
    }

    public function test_properti_fillable_berfungsi()
    {
        $roleData = [
            'kode_role' => 'RL-TEST',
            'name' => 'Role Test',
            'guard_name' => 'api'
        ];

        $role = Roles::create($roleData);

        $this->assertEquals($roleData['kode_role'], $role->kode_role);
        $this->assertEquals($roleData['name'], $role->name);
        $this->assertEquals($roleData['guard_name'], $role->guard_name);
    }
}
