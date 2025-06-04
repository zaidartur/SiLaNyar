<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\FormPengajuan;
use App\Models\SubKategori;
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

    #[Test]
    public function memastikan_bisa_menambah_multiple_parameter()
    {
        $kategori = Kategori::factory()->create();
        $parameters = ParameterUji::factory()->count(3)->create();
        
        // Attach multiple parameter dengan baku mutu berbeda
        $parameterData = $parameters->mapWithKeys(function ($parameter) {
            return [$parameter->id => ['baku_mutu' => fake()->randomFloat(2, 1, 100)]];
        })->toArray();
        
        $kategori->parameter()->attach($parameterData);
        
        $this->assertEquals(3, $kategori->parameter->count());
        foreach ($parameters as $parameter) {
            $this->assertTrue($kategori->parameter->contains($parameter));
            $this->assertNotNull($kategori->parameter->find($parameter->id)->pivot->baku_mutu);
        }
    }

    #[Test]
    public function memastikan_bisa_update_baku_mutu_parameter()
    {
        $kategori = Kategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        $bakuMutuAwal = 10.5;
        $bakuMutuBaru = 15.5;
        
        $kategori->parameter()->attach($parameter->id, ['baku_mutu' => $bakuMutuAwal]);
        $kategori->parameter()->updateExistingPivot($parameter->id, ['baku_mutu' => $bakuMutuBaru]);
        
        $this->assertEquals($bakuMutuBaru, $kategori->parameter->first()->pivot->baku_mutu);
    }

    #[Test]
    public function memastikan_nama_kategori_bersifat_unique()
    {
        $nama = 'Kategori Test Unique';
        
        // Buat kategori pertama
        Kategori::factory()->create(['nama' => $nama]);
        
        // Coba buat kategori dengan nama yang sama
        try {
            Kategori::factory()->create(['nama' => $nama]);
            $this->fail('Seharusnya gagal membuat kategori dengan nama yang sama');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertTrue(true);
            $this->assertStringContainsString('Duplicate entry', $e->getMessage());
        }
        
        // Pastikan hanya ada satu record dengan nama tersebut
        $this->assertEquals(1, Kategori::where('nama', $nama)->count());
    }

    #[Test]
    public function memastikan_relasi_dengan_subkategori_berfungsi()
    {
        $kategori = Kategori::factory()->create();
        $subkategori = SubKategori::factory()->create();
        
        $kategori->subkategori()->attach($subkategori->id);
        
        $this->assertTrue($kategori->subkategori->contains($subkategori));
    }

    #[Test]
    public function memastikan_bisa_update_harga()
    {
        $kategori = Kategori::factory()->create(['harga' => 100000]);
        $hargaBaru = 150000;
        
        $kategori->update(['harga' => $hargaBaru]);
        
        $this->assertEquals($hargaBaru, $kategori->fresh()->harga);
    }

    #[Test]
    public function memastikan_multiple_form_pengajuan_terhapus_saat_kategori_dihapus()
    {
        $kategori = Kategori::factory()->create();
        $formPengajuan = FormPengajuan::factory()
            ->count(3)
            ->create(['id_kategori' => $kategori->id]);
        
        $kategori->delete();
        
        foreach ($formPengajuan as $form) {
            $this->assertDatabaseMissing('form_pengajuan', ['id' => $form->id]);
        }
    }

    #[Test]
    public function memastikan_pivot_parameter_kategori_memiliki_timestamps()
    {
        $kategori = Kategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        
        $kategori->parameter()->attach($parameter->id, ['baku_mutu' => 10.5]);
        
        $pivot = $kategori->parameter->first()->pivot;
        $this->assertNotNull($pivot->created_at);
        $this->assertNotNull($pivot->updated_at);
    }

    #[Test]
    public function memastikan_harga_tidak_boleh_negatif()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Harga tidak boleh negatif');
        
        Kategori::factory()->create(['harga' => -1000]);
    }
}
