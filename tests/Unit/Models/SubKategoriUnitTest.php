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

    #[Test]
    public function memastikan_bisa_menambah_multiple_kategori()
    {
        $subKategori = SubKategori::factory()->create();
        $kategoris = Kategori::factory()->count(3)->create();
        
        $subKategori->kategori()->attach($kategoris);
        
        $this->assertEquals(3, $subKategori->kategori->count());
        foreach ($kategoris as $kategori) {
            $this->assertTrue($subKategori->kategori->contains($kategori));
        }
    }

    #[Test]
    public function memastikan_bisa_menambah_multiple_parameter_dengan_baku_mutu()
    {
        $subKategori = SubKategori::factory()->create();
        $parameters = ParameterUji::factory()->count(3)->create();
        
        $parameterData = $parameters->mapWithKeys(function ($parameter) {
            return [$parameter->id => ['baku_mutu' => fake()->numberBetween(1, 100) . ' mg/L']];
        })->toArray();
        
        $subKategori->parameter()->attach($parameterData);
        
        $this->assertEquals(3, $subKategori->parameter->count());
        foreach ($parameters as $parameter) {
            $this->assertTrue($subKategori->parameter->contains($parameter));
            $this->assertNotNull($subKategori->parameter->find($parameter->id)->pivot->baku_mutu);
        }
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $subKategori = SubKategori::factory()->create();
        
        $this->assertNotNull($subKategori->created_at);
        $this->assertNotNull($subKategori->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $subKategori->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $subKategori->updated_at);
    }
    
    // dikomen karena belum tahu harus unique atau tidak
    // #[Test]
    // public function memastikan_nama_bersifat_unique()
    // {
    //     $nama = 'Air Bersih Test';
    //     $subKategori = SubKategori::factory()->create(['nama' => $nama]);
        
    //     $this->expectException(\Illuminate\Database\QueryException::class);
        
    //     SubKategori::factory()->create(['nama' => $nama]);
    // }

    #[Test]
    public function memastikan_pivot_timestamps_parameter_berfungsi()
    {
        $subKategori = SubKategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        
        $subKategori->parameter()->attach($parameter->id, [
            'baku_mutu' => '100 mg/L'
        ]);
        
        $pivot = $subKategori->parameter->first()->pivot;
        $this->assertNotNull($pivot->created_at);
        $this->assertNotNull($pivot->updated_at);
    }

    #[Test]
    public function memastikan_bisa_update_baku_mutu_parameter()
    {
        $subKategori = SubKategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        $bakuMutuAwal = '100 mg/L';
        $bakuMutuBaru = '150 mg/L';
        
        $subKategori->parameter()->attach($parameter->id, ['baku_mutu' => $bakuMutuAwal]);
        $subKategori->parameter()->updateExistingPivot($parameter->id, ['baku_mutu' => $bakuMutuBaru]);
        
        $this->assertEquals($bakuMutuBaru, $subKategori->parameter->first()->pivot->baku_mutu);
    }

    #[Test]
    public function memastikan_bisa_detach_kategori()
    {
        $subKategori = SubKategori::factory()->create();
        $kategori = Kategori::factory()->create();
        
        $subKategori->kategori()->attach($kategori->id);
        $this->assertEquals(1, $subKategori->kategori->count());
        
        $subKategori->kategori()->detach($kategori->id);
        $this->assertEquals(0, $subKategori->fresh()->kategori->count());
    }
}
