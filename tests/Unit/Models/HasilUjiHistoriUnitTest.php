<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\HasilUji;
use App\Models\Pengujian;
use App\Models\ParameterUji;
use App\Models\HasilUjiHistori;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasilUjiHistoriUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_relasi_dengan_hasil_uji_berfungsi()
    {
        $histori = HasilUjiHistori::factory()
            ->for(HasilUji::factory(), 'hasil_uji')
            ->create();
            
        $this->assertInstanceOf(HasilUji::class, $histori->hasil_uji);
    }

    #[Test]
    public function memastikan_relasi_dengan_parameter_berfungsi()
    {
        $histori = HasilUjiHistori::factory()
            ->for(ParameterUji::factory(), 'parameter')
            ->create();
            
        $this->assertInstanceOf(ParameterUji::class, $histori->parameter);
    }

    #[Test]
    public function memastikan_relasi_dengan_pengujian_berfungsi()
    {
        $histori = HasilUjiHistori::factory()
            ->for(Pengujian::factory(), 'pengujian')
            ->create();
            
        $this->assertInstanceOf(Pengujian::class, $histori->pengujian);
    }

    #[Test]
    public function memastikan_relasi_dengan_user_berfungsi()
    {
        $histori = HasilUjiHistori::factory()
            ->for(User::factory(), 'user')
            ->create();
            
        $this->assertInstanceOf(User::class, $histori->user);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $histori = new HasilUjiHistori;
        
        $fillable = [
            'id_hasil_uji',
            'id_parameter',
            'id_pengujian',
            'id_user',
            'nilai',
            'keterangan',
            'status'
        ];
        
        $this->assertEquals($fillable, $histori->getFillable());
    }

    #[Test]
    public function memastikan_nilai_disimpan_sebagai_float()
    {
        $nilai = 12.34;
        $histori = HasilUjiHistori::factory()->create([
            'nilai' => $nilai
        ]);
        
        $this->assertIsFloat($histori->nilai);
        $this->assertEquals($nilai, $histori->nilai);
    }

    #[Test]
    public function memastikan_status_default_adalah_draf()
    {
        $histori = HasilUjiHistori::factory()->create([
            'status' => null
        ]);
        
        $this->assertEquals('draf', $histori->status);
    }

    #[Test]
    public function memastikan_keterangan_tidak_boleh_null()
    {
        $histori = HasilUjiHistori::factory()->create();
        
        $this->assertNotNull($histori->keterangan);
        $this->assertIsString($histori->keterangan);
    }
}
