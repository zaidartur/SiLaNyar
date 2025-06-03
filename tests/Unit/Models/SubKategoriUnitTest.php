<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\SubKategori;
use App\Models\Kategori;
use App\Models\ParameterUji;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubKategoriUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_subkategori_dibuat_secara_otomatis()
    {
        $subKategori = SubKategori::factory()->create();
        
        $prefix = 'SK-';
        $this->assertStringStartsWith($prefix, $subKategori->kode_subkategori);
        $this->assertEquals(6, strlen($subKategori->kode_subkategori));
    }

    #[Test]
    public function memastikan_kode_subkategori_berurutan()
    {
        $subKategoriPertama = SubKategori::factory()->create();
        $subKategoriKedua = SubKategori::factory()->create();
        
        $nomorPertama = (int)substr($subKategoriPertama->kode_subkategori, -3);
        $nomorKedua = (int)substr($subKategoriKedua->kode_subkategori, -3);
        
        $this->assertEquals($nomorPertama + 1, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_kategori_berfungsi()
    {
        $subKategori = SubKategori::factory()->create();
        $kategori = Kategori::factory()->create();
        
        $subKategori->kategori()->attach($kategori->id);
        
        $this->assertTrue($subKategori->kategori->contains($kategori));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $subKategori->kategori);
    }

    #[Test]
    public function memastikan_relasi_dengan_parameter_berfungsi()
    {
        $subKategori = SubKategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        $bakuMutu = '100 mg/L';
        
        $subKategori->parameter()->attach($parameter->id, ['baku_mutu' => $bakuMutu]);
        
        $this->assertTrue($subKategori->parameter->contains($parameter));
        $this->assertEquals($bakuMutu, $subKategori->parameter->first()->pivot->baku_mutu);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $subKategori = new SubKategori;
        
        $fillable = [
            'kode_subkategori',
            'nama'
        ];
        
        $this->assertEquals($fillable, $subKategori->getFillable());
    }

    #[Test]
    public function memastikan_nama_subkategori_bisa_disimpan()
    {
        $nama = 'Air Permukaan Kelas I';
        
        $subKategori = SubKategori::factory()->create([
            'nama' => $nama
        ]);
        
        $this->assertEquals($nama, $subKategori->nama);
    }

    #[Test]
    public function memastikan_kode_subkategori_unik()
    {
        $subKategori1 = SubKategori::factory()->create();
        $subKategori2 = SubKategori::factory()->create();
        
        $this->assertNotEquals($subKategori1->kode_subkategori, $subKategori2->kode_subkategori);
    }
}
