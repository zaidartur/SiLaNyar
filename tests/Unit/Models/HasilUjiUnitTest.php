<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\HasilUji;
use App\Models\Pengujian;
use App\Models\ParameterUji;
use App\Models\HasilUjiHistori;
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
    public function memastikan_relasi_dengan_parameter_uji_berfungsi()
    {
        $hasilUji = HasilUji::factory()
            ->for(ParameterUji::factory(), 'parameter')
            ->create();
            
        $this->assertInstanceOf(ParameterUji::class, $hasilUji->parameter);
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
        
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $hasilUji->riwayat);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $hasilUji = new HasilUji;
        
        $fillable = [
            'kode_hasil_uji',
            'id_parameter',
            'id_pengujian',
            'nilai',
            'keterangan',
            'status',
            'file_pdf'
        ];
        
        $this->assertEquals($fillable, $hasilUji->getFillable());
    }

    #[Test]
    public function memastikan_status_default_adalah_draf()
    {
        $hasilUji = HasilUji::factory()->create([
            'status' => null
        ]);
        
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
    public function memastikan_nilai_disimpan_sebagai_float()
    {
        $nilai = 12.34;
        $hasilUji = HasilUji::factory()->create([
            'nilai' => $nilai
        ]);
        
        $this->assertIsFloat($hasilUji->nilai);
        $this->assertEquals($nilai, $hasilUji->nilai);
    }
}
