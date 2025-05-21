<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\FormPengajuan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KategoriUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_kategori_dibuat_secara_otomatis()
    {
        $kategori = Kategori::factory()->create();
        
        $this->assertStringStartsWith('DK-', $kategori->kode_kategori);
        $this->assertEquals(6, strlen($kategori->kode_kategori));
    }

    #[Test]
    public function memastikan_kode_kategori_berurutan()
    {
        $kategoriPertama = Kategori::factory()->create();
        $kategoriKedua = Kategori::factory()->create();
        
        $nomorPertama = (int)substr($kategoriPertama->kode_kategori, -3);
        $nomorKedua = (int)substr($kategoriKedua->kode_kategori, -3);
        
        $this->assertEquals(1, $nomorPertama);
        $this->assertEquals(2, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_parameter_berfungsi()
    {
        $kategori = Kategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        
        $kategori->parameter()->attach($parameter->id, ['baku_mutu' => 10.5]);
        
        $this->assertTrue($kategori->parameter->contains($parameter));
        $this->assertEquals(10.5, $kategori->parameter->first()->pivot->baku_mutu);
    }

    #[Test]
    public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    {
        $kategori = Kategori::factory()->create();
        $formPengajuan = FormPengajuan::factory()->create([
            'id_kategori' => $kategori->id
        ]);
        
        $this->assertTrue($kategori->form_pengajuan->contains($formPengajuan));
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $kategori = new Kategori;
        
        $fillable = [
            'kode_kategori',
            'nama',
            'harga'
        ];
        
        $this->assertEquals($fillable, $kategori->getFillable());
    }

    #[Test]
    public function memastikan_nama_tidak_boleh_null()
    {
        $kategori = Kategori::factory()->create();
        
        $this->assertNotNull($kategori->nama);
        $this->assertIsString($kategori->nama);
    }

    #[Test]
    public function memastikan_harga_berupa_integer()
    {
        $kategori = Kategori::factory()->create();
        
        $this->assertIsInt($kategori->harga);
        $this->assertGreaterThan(0, $kategori->harga);
    }

    #[Test]
    public function memastikan_kode_kategori_bersifat_unique()
    {
        $kategoriPertama = Kategori::factory()->create();
        
        Kategori::factory()->create(['kode_kategori' => $kategoriPertama->kode_kategori]);
        
        $this->assertEquals(1, Kategori::where('kode_kategori', $kategoriPertama->kode_kategori)->count());
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $kategori = Kategori::factory()->create();
        
        $this->assertNotNull($kategori->created_at);
        $this->assertNotNull($kategori->updated_at);
    }
}
