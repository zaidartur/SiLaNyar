<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Permissions;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;

class PermissionsUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_permission_dibuat_secara_otomatis()
    {
        $permission = Permissions::factory()->create();
        
        $this->assertStringStartsWith('PS-', $permission->kode_permission);
        $this->assertEquals(6, strlen($permission->kode_permission));
    }

    #[Test]
    public function memastikan_kode_permission_berurutan()
    {
        $permissionPertama = Permissions::factory()->create();
        $permissionKedua = Permissions::factory()->create();
        
        $nomorPertama = (int)substr($permissionPertama->kode_permission, -3);
        $nomorKedua = (int)substr($permissionKedua->kode_permission, -3);
        
        $this->assertEquals(1, $nomorPertama);
        $this->assertEquals(2, $nomorKedua);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $permission = new Permissions;
        
        $fillable = [
            'kode_permission',
            'name',
            'guard_name'
        ];
        
        $this->assertEquals($fillable, $permission->getFillable());
    }

    #[Test]
    public function memastikan_guard_name_default_adalah_web()
    {
        $permission = Permissions::factory()->create();
        
        $this->assertEquals('web', $permission->guard_name);
    }

    #[Test]
    public function memastikan_format_nama_permission_reguler_valid()
    {
        $permission = Permissions::factory()->create();
        
        $this->assertMatchesRegularExpression(
            '/^(lihat|tambah|edit|hapus|detail|verifikasi)-[a-z_]+$/', 
            $permission->name
        );
    }

    #[Test]
    public function memastikan_format_nama_permission_special_valid()
    {
        $permission = Permissions::factory()->special()->create();
        
        $this->assertMatchesRegularExpression(
            '/^kelola-(permission|role|user|system)$/', 
            $permission->name
        );
    }

    #[Test]
    public function memastikan_kode_permission_bersifat_unique()
    {
        $permission = Permissions::factory()->create();
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        
        Permissions::create([
            'kode_permission' => $permission->kode_permission,
            'name' => 'test-permission',
            'guard_name' => 'web'
        ]);
    }

    #[Test]
    public function memastikan_nama_permission_bersifat_unique()
    {
        $permission = Permissions::factory()->create();
        $this->expectException(PermissionAlreadyExists::class);
        
        Permissions::create([
            'name' => $permission->name,
            'guard_name' => 'web'
        ]);
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $permission = Permissions::factory()->create();
        
        $this->assertNotNull($permission->created_at);
        $this->assertNotNull($permission->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $permission->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $permission->updated_at);
    }

    #[Test]
    public function memastikan_permission_dapat_diupdate()
    {
        $permission = Permissions::factory()->create();
        $newName = 'lihat-test';
        
        $permission->name = $newName;
        $permission->save();
        
        $this->assertEquals($newName, $permission->fresh()->name);
    }
}
