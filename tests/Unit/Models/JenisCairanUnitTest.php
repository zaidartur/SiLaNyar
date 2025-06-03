<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\JenisCairan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JenisCairanUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_jenis_cairan_dibuat_secara_otomatis()
    {
        $jenisCairan = JenisCairan::factory()->create();
        
        $this->assertStringStartsWith('JC-', $jenisCairan->kode_jenis_cairan);
        $this->assertEquals(6, strlen($jenisCairan->kode_jenis_cairan));
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $jenisCairan = new JenisCairan;
        
        $fillable = [
            'kode_jenis_cairan',
            'nama',
            'batas_minimum',
            'batas_maksimum'
        ];
        
        $this->assertEquals($fillable, $jenisCairan->getFillable());
    }

    #[Test]
    public function memastikan_nama_tidak_boleh_null()
    {
        $jenisCairan = JenisCairan::factory()->create();
        
        $this->assertNotNull($jenisCairan->nama);
        $this->assertIsString($jenisCairan->nama);
    }

    #[Test]
    public function memastikan_batas_minimum_berupa_float()
    {
        $jenisCairan = JenisCairan::factory()->create();
        
        $this->assertIsFloat($jenisCairan->batas_minimum);
    }

    #[Test]
    public function memastikan_batas_maksimum_berupa_float()
    {
        $jenisCairan = JenisCairan::factory()->create();
        
        $this->assertIsFloat($jenisCairan->batas_maksimum);
    }

    #[Test]
    public function memastikan_batas_maksimum_lebih_besar_dari_minimum()
    {
        $jenisCairan = JenisCairan::factory()->create();
        
        $this->assertGreaterThan($jenisCairan->batas_minimum, $jenisCairan->batas_maksimum);
    }

    #[Test]
    public function memastikan_model_tidak_menggunakan_timestamps()
    {
        $jenisCairan = new JenisCairan;
        
        $this->assertFalse($jenisCairan->timestamps);
    }

    #[Test]
    public function memastikan_kode_jenis_cairan_bersifat_unique()
    {
        $jenisCairan = JenisCairan::factory()->create();
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        JenisCairan::factory()->create([
            'kode_jenis_cairan' => $jenisCairan->kode_jenis_cairan
        ]);
    }

    #[Test]
    public function memastikan_kode_jenis_cairan_berurutan()
    {
        $jenisCairanPertama = JenisCairan::factory()->create();
        $jenisCairanKedua = JenisCairan::factory()->create();
        
        $nomorPertama = (int)substr($jenisCairanPertama->kode_jenis_cairan, -3);
        $nomorKedua = (int)substr($jenisCairanKedua->kode_jenis_cairan, -3);
        
        $this->assertEquals($nomorPertama + 1, $nomorKedua);
    }
}
