<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Aduan;
use App\Models\User;
use App\Models\HasilUji;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AduanUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_aduan_dibuat_secara_otomatis()
    {
        $aduan = Aduan::factory()->create();
        
        $prefix = 'AU-';
        $this->assertStringStartsWith($prefix, $aduan->kode_aduan);
        $this->assertEquals(6, strlen($aduan->kode_aduan));
    }

    #[Test]
    public function memastikan_kode_aduan_berurutan()
    {
        $aduanPertama = Aduan::factory()->create();
        $aduanKedua = Aduan::factory()->create();
        
        $nomorPertama = (int)substr($aduanPertama->kode_aduan, -3);
        $nomorKedua = (int)substr($aduanKedua->kode_aduan, -3);
        
        $this->assertEquals($nomorPertama + 1, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_hasil_uji_berfungsi()
    {
        $aduan = Aduan::factory()
            ->for(HasilUji::factory(), 'hasil_uji')
            ->create();
            
        $this->assertInstanceOf(HasilUji::class, $aduan->hasil_uji);
    }

    #[Test]
    public function memastikan_relasi_dengan_user_berfungsi()
    {
        $aduan = Aduan::factory()
            ->for(User::factory(), 'user')
            ->create();
            
        $this->assertInstanceOf(User::class, $aduan->user);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $aduan = new Aduan;
        
        $fillable = [
            'kode_aduan',
            'id_hasil_uji',
            'id_user',
            'masalah',
            'perbaikan',
            'status',
            'diverifikasi_oleh'
        ];
        
        $this->assertEquals($fillable, $aduan->getFillable());
    }

    #[Test]
    public function memastikan_status_bisa_null()
    {
        $aduan = Aduan::factory()->create([
            'status' => null
        ]);
        
        $this->assertNull($aduan->status);
    }

    #[Test]
    public function memastikan_diverifikasi_oleh_bisa_null()
    {
        $aduan = Aduan::factory()->create([
            'diverifikasi_oleh' => null
        ]);
        
        $this->assertNull($aduan->diverifikasi_oleh);
    }

    #[Test]
    public function memastikan_status_hanya_menerima_nilai_yang_valid()
    {
        $statusValid = ['diterima_administrasi', 'diterima_pengujian', 'ditolak'];
        
        $aduan = Aduan::factory()->create([
            'status' => $statusValid[0]
        ]);
        
        $this->assertContains($aduan->status, $statusValid);
    }
}
