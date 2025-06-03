<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\HasilUji;
use App\Models\Pengujian;
use App\Models\HasilUjiHistori;
use App\Models\Aduan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasilUjiUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_hasil_uji_dibuat_secara_otomatis()
    {
        $hasilUji = HasilUji::factory()->create();
        
        $prefix = 'HU-'.date('my');
        $this->assertStringStartsWith($prefix, $hasilUji->kode_hasil_uji);
        $this->assertEquals(11, strlen($hasilUji->kode_hasil_uji));
    }

    #[Test]
    public function memastikan_kode_hasil_uji_berurutan()
    {
        $hasilPertama = HasilUji::factory()->create();
        $hasilKedua = HasilUji::factory()->create();
        
        $nomorPertama = (int)substr($hasilPertama->kode_hasil_uji, -3);
        $nomorKedua = (int)substr($hasilKedua->kode_hasil_uji, -3);
        
        $this->assertEquals($nomorPertama + 1, $nomorKedua);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $hasilUji = new HasilUji;
        
        $fillable = [
            'kode_hasil_uji',
            'id_pengujian',
            'status',
            'file_pdf',
            'diverifikasi_oleh',
        ];
        
        $this->assertEquals($fillable, $hasilUji->getFillable());
    }

    #[Test]
    public function memastikan_relasi_dengan_pengujian_berfungsi()
    {
        $hasilUji = HasilUji::factory()
            ->for(Pengujian::factory(), 'pengujian')
            ->create();
            
        $this->assertInstanceOf(Pengujian::class, $hasilUji->pengujian);
    }

    #[Test]
    public function memastikan_relasi_dengan_riwayat_berfungsi()
    {
        $hasilUji = HasilUji::factory()->create();
        $riwayat = HasilUjiHistori::factory()->create([
            'id_hasil_uji' => $hasilUji->id
        ]);
        
        $this->assertTrue($hasilUji->riwayat->contains($riwayat));
    }

    #[Test]
    public function memastikan_relasi_dengan_aduan_berfungsi()
    {
        $hasilUji = HasilUji::factory()->create();
        $aduan = Aduan::factory()->create([
            'id_hasil_uji' => $hasilUji->id
        ]);
        
        $this->assertInstanceOf(Aduan::class, $hasilUji->aduan);
    }

    #[Test]
    public function memastikan_status_default_adalah_draf()
    {
        $hasilUji = HasilUji::factory()->make([
            'status' => null
        ]);
        
        $hasilUji->save();
        
        $this->assertEquals('draf', $hasilUji->status);
    }

    #[Test]
    public function memastikan_file_pdf_bisa_null()
    {
        $hasilUji = HasilUji::factory()->create([
            'file_pdf' => null
        ]);
        
        $this->assertNull($hasilUji->file_pdf);
    }

    #[Test]
    public function memastikan_diverifikasi_oleh_bisa_null()
    {
        $hasilUji = HasilUji::factory()->create([
            'diverifikasi_oleh' => null
        ]);
        
        $this->assertNull($hasilUji->diverifikasi_oleh);
    }

    #[Test]
    public function memastikan_status_aduan_mengembalikan_status_aduan_jika_ada()
    {
        $hasilUji = HasilUji::factory()->create();
        $aduan = Aduan::factory()->create([
            'id_hasil_uji' => $hasilUji->id,
            'status' => 'diterima_administrasi'
        ]);
        
        $this->assertEquals('diterima_administrasi', $hasilUji->getStatusAduan());
    }

    #[Test]
    public function memastikan_status_aduan_mengembalikan_status_hasil_uji_jika_tidak_ada_aduan()
    {
        $hasilUji = HasilUji::factory()->create([
            'status' => 'selesai'
        ]);
        
        $this->assertEquals('selesai', $hasilUji->getStatusAduan());
    }
}
