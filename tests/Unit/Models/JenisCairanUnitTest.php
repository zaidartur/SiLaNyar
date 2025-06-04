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

    #[Test]
    public function memastikan_batas_minimum_tidak_boleh_null()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        JenisCairan::factory()->create([
            'batas_minimum' => null
        ]);
    }

    #[Test]
    public function memastikan_batas_maksimum_bisa_null()
    {
        $jenisCairan = JenisCairan::factory()->create([
            'batas_maksimum' => null
        ]);
        
        $this->assertNull($jenisCairan->batas_maksimum);
    }

    #[Test]
    public function memastikan_bisa_update_nama()
    {
        $jenisCairan = JenisCairan::factory()->create();
        $namaBaru = 'Air Limbah Industri';
        
        $jenisCairan->nama = $namaBaru;
        $jenisCairan->save();
        
        $this->assertEquals($namaBaru, $jenisCairan->fresh()->nama);
    }

    #[Test]
    public function memastikan_bisa_update_batas()
    {
        $jenisCairan = JenisCairan::factory()->create();
        $batasMinimumBaru = 150.5;
        $batasMaksimumBaru = 1500.5;
        
        $jenisCairan->update([
            'batas_minimum' => $batasMinimumBaru,
            'batas_maksimum' => $batasMaksimumBaru
        ]);
        
        $this->assertEquals($batasMinimumBaru, $jenisCairan->fresh()->batas_minimum);
        $this->assertEquals($batasMaksimumBaru, $jenisCairan->fresh()->batas_maksimum);
    }

    #[Test]
    public function memastikan_nama_bersifat_unique()
    {
        $nama = 'Air Limbah Khusus';
        
        // Buat jenis cairan pertama
        JenisCairan::factory()->create([
            'nama' => $nama
        ]);
        
        // Pastikan tidak bisa membuat jenis cairan dengan nama yang sama
        try {
            JenisCairan::factory()->create([
                'nama' => $nama
            ]);
            
            $this->fail('Seharusnya gagal membuat jenis cairan dengan nama yang sama');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertTrue(true);
            // Memastikan error karena unique constraint
            $this->assertStringContainsString('Duplicate entry', $e->getMessage());
        }
        
        // Verifikasi hanya ada satu record dengan nama tersebut
        $this->assertEquals(1, JenisCairan::where('nama', $nama)->count());
    }
    
    #[Test]
    public function memastikan_data_valid_saat_create()
    {
        $data = [
            'nama' => 'Air Limbah Test',
            'batas_minimum' => 100.5,
            'batas_maksimum' => 1000.5
        ];
        
        $jenisCairan = JenisCairan::create($data);
        
        $this->assertInstanceOf(JenisCairan::class, $jenisCairan);
        $this->assertEquals($data['nama'], $jenisCairan->nama);
        $this->assertEquals($data['batas_minimum'], $jenisCairan->batas_minimum);
        $this->assertEquals($data['batas_maksimum'], $jenisCairan->batas_maksimum);
        $this->assertStringStartsWith('JC-', $jenisCairan->kode_jenis_cairan);
    }

    #[Test]
    public function memastikan_format_batas_valid()
    {
        $jenisCairan = JenisCairan::factory()->create();
        
        $this->assertMatchesRegularExpression('/^\d+\.\d{1,2}$/', (string)$jenisCairan->batas_minimum);
        if ($jenisCairan->batas_maksimum) {
            $this->assertMatchesRegularExpression('/^\d+\.\d{1,2}$/', (string)$jenisCairan->batas_maksimum);
        }
    }
}
