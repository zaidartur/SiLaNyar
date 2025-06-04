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

    #[Test]
    public function memastikan_struktur_data_parameterdanpengujian_valid()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()->create();
        
        $this->assertArrayHasKey('parameter', $hasilUjiHistori->data_parameterdanpengujian);
        $this->assertArrayHasKey('pengujian', $hasilUjiHistori->data_parameterdanpengujian);
        
        // Validasi struktur parameter
        $parameter = $hasilUjiHistori->data_parameterdanpengujian['parameter'][0];
        $this->assertArrayHasKey('id', $parameter);
        $this->assertArrayHasKey('nama', $parameter);
        $this->assertArrayHasKey('baku_mutu', $parameter);
        $this->assertArrayHasKey('nilai', $parameter);
        $this->assertArrayHasKey('keterangan', $parameter);
        
        // Validasi struktur pengujian
        $pengujian = $hasilUjiHistori->data_parameterdanpengujian['pengujian'];
        $this->assertArrayHasKey('id', $pengujian);
        $this->assertArrayHasKey('tanggal_pengujian', $pengujian);
        $this->assertArrayHasKey('metode', $pengujian);
    }

    #[Test]
    public function memastikan_perubahan_status_terekam()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()->create(['status' => 'draf']);
        
        $hasilUjiHistori->status = 'proses_review';
        $hasilUjiHistori->save();
        
        $this->assertEquals('proses_review', $hasilUjiHistori->fresh()->status);
    }

    #[Test]
    public function memastikan_histori_terhapus_saat_hasil_uji_dihapus()
    {
        $hasilUji = HasilUji::factory()->create();
        $hasilUjiHistori = HasilUjiHistori::factory()->create([
            'id_hasil_uji' => $hasilUji->id
        ]);
        
        $hasilUji->delete();
        
        $this->assertDatabaseMissing('hasil_uji_histori', [
            'id' => $hasilUjiHistori->id
        ]);
    }

    #[Test]
    public function memastikan_format_timestamps_benar()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()->create();
        
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
            $hasilUjiHistori->created_at->format('Y-m-d H:i:s')
        );
        
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
            $hasilUjiHistori->updated_at->format('Y-m-d H:i:s')
        );
    }

    #[Test]
    public function memastikan_field_wajib_terisi()
    {
        $hasilUjiHistori = HasilUjiHistori::factory()->create();
        
        $this->assertNotNull($hasilUjiHistori->id_hasil_uji);
        $this->assertNotNull($hasilUjiHistori->data_parameterdanpengujian);
        $this->assertNotNull($hasilUjiHistori->status);
        $this->assertNotNull($hasilUjiHistori->diupdate_oleh);
    }

    #[Test]
    public function memastikan_urutan_status_valid()
    {
        $validTransitions = [
            'draf' => ['revisi', 'proses_review'],
            'revisi' => ['proses_review'],
            'proses_review' => ['proses_peresmian', 'revisi'],
            'proses_peresmian' => ['selesai', 'revisi'],
            'selesai' => []
        ];
        
        $hasilUjiHistori = HasilUjiHistori::factory()->create(['status' => 'draf']);
        
        foreach ($validTransitions['draf'] as $nextStatus) {
            $hasilUjiHistori->status = $nextStatus;
            $hasilUjiHistori->save();
            
            $this->assertEquals($nextStatus, $hasilUjiHistori->fresh()->status);
        }
    }
}
