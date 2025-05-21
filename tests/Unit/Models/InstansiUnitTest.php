<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Instansi;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstansiUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_instansi_dibuat_secara_otomatis()
    {
        $instansi = Instansi::factory()->create();
        
        $this->assertStringStartsWith('IN-', $instansi->kode_instansi);
        $this->assertEquals(6, strlen($instansi->kode_instansi));
    }

    #[Test]
    public function memastikan_kode_instansi_berurutan()
    {
        $instansiPertama = Instansi::factory()->create();
        $instansiKedua = Instansi::factory()->create();
        
        $nomorPertama = (int)substr($instansiPertama->kode_instansi, -3);
        $nomorKedua = (int)substr($instansiKedua->kode_instansi, -3);
        
        $this->assertEquals($nomorPertama + 1, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_user_berfungsi()
    {
        $instansi = Instansi::factory()
            ->for(User::factory())
            ->create();
            
        $this->assertInstanceOf(User::class, $instansi->user);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $instansi = new Instansi;
        
        $fillable = [
            'kode_instansi',
            'id_user',
            'nama',
            'tipe',
            'alamat',
            'wilayah',
            'desa/kelurahan',
            'email',
            'no_telepon',
            'posisi/jabatan',
            'departemen/divisi',
            'status_verifikasi',
            'diverifikasi_oleh'
        ];
        
        $this->assertEquals($fillable, $instansi->getFillable());
    }

    #[Test]
    public function memastikan_status_verifikasi_default_adalah_diproses()
    {
        $instansi = Instansi::factory()->create([
            'status_verifikasi' => null
        ]);
        
        $this->assertEquals('diproses', $instansi->status_verifikasi);
    }

    #[Test]
    public function memastikan_tipe_instansi_valid()
    {
        $instansi = Instansi::factory()->create();
        
        $this->assertContains($instansi->tipe, ['swasta', 'pemerintahan', 'pribadi']);
    }

    #[Test]
    public function memastikan_email_bersifat_unique()
    {
        $instansiPertama = Instansi::factory()->create();
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Instansi::factory()->create([
            'email' => $instansiPertama->email
        ]);
    }

    #[Test]
    public function memastikan_kode_instansi_bersifat_unique()
    {
        $instansiPertama = Instansi::factory()->create();
        
        Instansi::factory()->create(['kode_instansi' => $instansiPertama->kode_instansi]);
        
        $this->assertEquals(1, Instansi::where('kode_instansi', $instansiPertama->kode_instansi)->count());
    }
}
