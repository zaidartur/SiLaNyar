<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\JenisCairan;
use App\Models\ParameterUji;
use App\Models\FormPengajuan;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use App\Models\Pengujian;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormPengajuanUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_pengajuan_dibuat_secara_otomatis()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        
        $prefix = 'DP-'.date('my');
        $this->assertStringStartsWith($prefix, $formPengajuan->kode_pengajuan);
        $this->assertEquals(10, strlen($formPengajuan->kode_pengajuan));
    }

    #[Test]
    public function memastikan_kode_pengajuan_berurutan()
    {
        $formPertama = FormPengajuan::factory()->create();
        $formKedua = FormPengajuan::factory()->create();
        
        $nomorPertama = (int)substr($formPertama->kode_pengajuan, -3);
        $nomorKedua = (int)substr($formKedua->kode_pengajuan, -3);
        
        $this->assertEquals($nomorPertama + 1, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_isntansi_berfungsi()
    {
        $formPengajuan = FormPengajuan::factory()
            ->for(Instansi::factory(), 'instansi')
            ->create();
            
        $this->assertInstanceOf(Instansi::class, $formPengajuan->instansi);
    }

    #[Test]
    public function memastikan_relasi_dengan_kategori_berfungsi()
    {
        $formPengajuan = FormPengajuan::factory()
            ->for(Kategori::factory(), 'kategori')
            ->create();
            
        $this->assertInstanceOf(Kategori::class, $formPengajuan->kategori);
    }

    #[Test]
    public function memastikan_relasi_dengan_jenis_cairan_berfungsi()
    {
        $formPengajuan = FormPengajuan::factory()
            ->for(JenisCairan::factory(), 'jenis_cairan')
            ->create();
            
        $this->assertInstanceOf(JenisCairan::class, $formPengajuan->jenis_cairan);
    }

    #[Test]
    public function memastikan_relasi_dengan_parameter_berfungsi()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        $parameter = ParameterUji::factory()->create();
        
        $formPengajuan->parameter()->attach($parameter->id);
        
        $this->assertTrue($formPengajuan->parameter->contains($parameter));
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $formPengajuan = new FormPengajuan;
        
        // Sesuaikan dengan fillable yang ada di model FormPengajuan
        $fillable = [
            'kode_pengajuan',
            'id_instansi',
            'id_kategori',
            'id_jenis_cairan',
            'volume_sampel',
            'status_pengajuan',
            'metode_pengambilan',
            'lokasi'
        ];
        
        $this->assertEquals($fillable, $formPengajuan->getFillable());
    }

    #[Test]
    public function memastikan_status_pengajuan_default_adalah_proses_validasi()
    {
        $formPengajuan = FormPengajuan::factory()->make([
            'status_pengajuan' => null
        ]);
        
        $formPengajuan->save();
        
        $this->assertEquals('proses_validasi', $formPengajuan->status_pengajuan);
    }

    #[Test]
    public function memastikan_lokasi_null_ketika_metode_diantar()
    {
        $formPengajuan = FormPengajuan::factory()->diantar()->create();
        
        $this->assertNull($formPengajuan->lokasi);
    }

    #[Test]
    public function memastikan_lokasi_terisi_ketika_metode_diambil()
    {
        $formPengajuan = FormPengajuan::factory()->diambil()->create();
        
        $this->assertNotNull($formPengajuan->lokasi);
    }

    #[Test]
    public function memastikan_relasi_dengan_jadwal_berfungsi()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        $jadwal = Jadwal::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id
        ]);
        
        $this->assertInstanceOf(Jadwal::class, $formPengajuan->jadwal);
        $this->assertEquals($jadwal->id, $formPengajuan->jadwal->id);
    }

    #[Test]
    public function memastikan_relasi_dengan_pembayaran_berfungsi()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        $pembayaran = Pembayaran::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id
        ]);
        
        $this->assertInstanceOf(Pembayaran::class, $formPengajuan->pembayaran);
        $this->assertEquals($pembayaran->id, $formPengajuan->pembayaran->id);
    }

    #[Test]
    public function memastikan_relasi_dengan_pengujian_berfungsi()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        $pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id
        ]);
        
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $formPengajuan->pengujian);
        $this->assertTrue($formPengajuan->pengujian->contains($pengujian));
    }
}
