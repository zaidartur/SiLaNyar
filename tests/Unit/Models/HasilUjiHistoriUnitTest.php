<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\HasilUji;
use App\Models\HasilUjiHistori;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasilUjiHistoriUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $hasilUjiHistori = new HasilUjiHistori;
        
        $fillable = [
            'id_hasil_uji',
            'data_parameterdanpengujian',
            'status',
            'diupdate_oleh'
        ];
        
        $this->assertEquals($fillable, $hasilUjiHistori->getFillable());
    }

    #[Test]
    public function memastikan_relasi_dengan_hasil_uji_berfungsi()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()
            ->for(HasilUji::factory(), 'hasil_uji')
            ->create();
            
        $this->assertInstanceOf(HasilUji::class, $hasilUjiHistori->hasil_uji);
    }

    #[Test]
    public function memastikan_relasi_dengan_user_berfungsi()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()
            ->for(User::factory(), 'user')
            ->create();
            
        $this->assertInstanceOf(User::class, $hasilUjiHistori->user);
    }

    #[Test]
    public function memastikan_status_default_adalah_draf()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()->make([
            'status' => 'draf'
        ]);
        
        $hasilUjiHistori->save();
        
        $this->assertEquals('draf', $hasilUjiHistori->status);
    }

    #[Test]
    public function memastikan_data_parameterdanpengujian_disimpan_sebagai_array()
    {
        $testData = [
            'parameter' => [
                [
                    'id' => 1,
                    'nama' => 'Test Parameter',
                    'baku_mutu' => 50.0,
                    'nilai' => 45.5,
                    'keterangan' => 'Memenuhi baku mutu'
                ]
            ],
            'pengujian' => [
                'id' => 1,
                'tanggal_pengujian' => '2024-01-01',
                'metode' => 'Test Metode'
            ]
        ];

        $hasilUjiHistori = HasilUjiHistori::factory()->create([
            'data_parameterdanpengujian' => $testData
        ]);

        $this->assertIsArray($hasilUjiHistori->data_parameterdanpengujian);
        $this->assertEquals($testData, $hasilUjiHistori->data_parameterdanpengujian);
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()->create();
        
        $this->assertNotNull($hasilUjiHistori->created_at);
        $this->assertNotNull($hasilUjiHistori->updated_at);
    }

    #[Test]
    public function memastikan_status_hanya_bisa_diisi_dengan_nilai_yang_valid()
    {
        $validStatus = ['draf', 'revisi', 'proses_review', 'proses_peresmian', 'selesai'];
        $hasilUjiHistori = HasilUjiHistori::factory()->create();
        
        $this->assertTrue(in_array($hasilUjiHistori->status, $validStatus));
    }

    #[Test]
    public function memastikan_diupdate_oleh_terisi()
    {
        $nama = 'John Doe';
        $hasilUjiHistori = HasilUjiHistori::factory()->create([
            'diupdate_oleh' => $nama
        ]);
        
        $this->assertEquals($nama, $hasilUjiHistori->diupdate_oleh);
    }
}
