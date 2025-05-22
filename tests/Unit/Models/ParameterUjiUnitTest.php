<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\HasilUji;
use App\Models\Kategori;
use App\Models\Pengujian;
use App\Models\ParameterUji;
use App\Models\FormPengajuan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParameterUjiUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_parameter_dibuat_secara_otomatis()
    {
        $parameter = ParameterUji::factory()->create();
        
        $this->assertStringStartsWith('PR-', $parameter->kode_parameter);
        $this->assertEquals(6, strlen($parameter->kode_parameter));
    }

    #[Test]
    public function memastikan_kode_parameter_berurutan()
    {
        $parameterPertama = ParameterUji::factory()->create();
        $parameterKedua = ParameterUji::factory()->create();
        
        $nomorPertama = (int)substr($parameterPertama->kode_parameter, -3);
        $nomorKedua = (int)substr($parameterKedua->kode_parameter, -3);
        
        $this->assertEquals(1, $nomorPertama);
        $this->assertEquals(2, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_pengujian_berfungsi()
    {
        $parameter = ParameterUji::factory()->create();
        $pengujian = Pengujian::factory()->create();
        $kodeHasilUji = 'HU-' . date('my') . '-001';
        
        $parameter->pengujian()->attach($pengujian->id, [
            'kode_hasil_uji' => $kodeHasilUji,
            'nilai' => 10.5,
            'keterangan' => 'Test'
        ]);
        
        $this->assertTrue($parameter->pengujian->contains($pengujian));
        $this->assertEquals(10.5, $parameter->pengujian->first()->pivot->nilai);
    }

    #[Test]
    public function memastikan_relasi_dengan_hasil_uji_berfungsi()
    {
        $parameter = ParameterUji::factory()->create();
        $hasilUji = HasilUji::factory()->create([
            'id_parameter' => $parameter->id,
            'kode_hasil_uji' => 'HU-' . date('my') . '-001'
        ]);
        
        $this->assertTrue($parameter->hasil_uji->contains($hasilUji));
    }

    #[Test]
    public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    {
        $parameter = ParameterUji::factory()->create();
        $formPengajuan = FormPengajuan::factory()->create();
        
        $parameter->form_pengajuan()->attach($formPengajuan->id);
        
        $this->assertTrue($parameter->form_pengajuan->contains($formPengajuan));
    }

    #[Test]
    public function memastikan_relasi_dengan_kategori_berfungsi()
    {
        $parameter = ParameterUji::factory()->create();
        $kategori = Kategori::factory()->create();
        
        $parameter->kategori()->attach($kategori->id, ['baku_mutu' => 10.5]);
        
        $this->assertTrue($parameter->kategori->contains($kategori));
        $this->assertEquals(10.5, $parameter->kategori->first()->pivot->baku_mutu);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $parameter = new ParameterUji;
        
        $fillable = [
            'kode_parameter',
            'nama_parameter',
            'satuan',
            'harga'
        ];
        
        $this->assertEquals($fillable, $parameter->getFillable());
    }

    #[Test]
    public function memastikan_nama_parameter_tidak_boleh_null()
    {
        $parameter = ParameterUji::factory()->create();
        
        $this->assertNotNull($parameter->nama_parameter);
        $this->assertIsString($parameter->nama_parameter);
    }

    #[Test]
    public function memastikan_harga_berupa_integer()
    {
        $parameter = ParameterUji::factory()->create();
        
        $this->assertIsInt($parameter->harga);
        $this->assertGreaterThan(0, $parameter->harga);
    }

    #[Test]
    public function memastikan_kode_parameter_bersifat_unique()
    {
        $parameterPertama = ParameterUji::factory()->create();
        
        $this->assertEquals(1, ParameterUji::where('kode_parameter', $parameterPertama->kode_parameter)->count());
    }
}
