<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\HasilUji;
use App\Models\Kategori;
use App\Models\Pengujian;
use App\Models\ParameterUji;
use App\Models\FormPengajuan;
use App\Models\SubKategori;
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
        
        $parameter->pengujian()->attach($pengujian->id, [
            'nilai' => 10.5,
            'keterangan' => 'Test'
        ]);
        
        $this->assertTrue($parameter->pengujian->contains($pengujian));
        $this->assertEquals(10.5, $parameter->pengujian->first()->pivot->nilai);
    }

    #[Test]
    public function memastikan_relasi_tidak_langsung_dengan_hasil_uji_tetapi_melalui_pengujian()
    {
        $parameter = ParameterUji::factory()->create();
        $pengujian = Pengujian::factory()->create();
        
        // Hubungkan parameter dengan pengujian terlebih dahulu
        $parameter->pengujian()->attach($pengujian->id, [
            'nilai' => 10.5,
            'keterangan' => 'Test'
        ]);
        
        // Buat hasil uji yang terkait dengan pengujian
        $hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $pengujian->id
        ]);
        
        $this->assertTrue($parameter->pengujian->contains($pengujian));
        $this->assertEquals($pengujian->id, $hasilUji->id_pengujian);
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

    #[Test]
    public function memastikan_bisa_menambah_multiple_kategori()
    {
        $parameter = ParameterUji::factory()->create();
        $kategoris = Kategori::factory()->count(3)->create();
        
        $kategoriData = $kategoris->mapWithKeys(function ($kategori) {
            return [$kategori->id => ['baku_mutu' => fake()->randomFloat(2, 1, 100)]];
        })->toArray();
        
        $parameter->kategori()->attach($kategoriData);
        
        $this->assertEquals(3, $parameter->kategori->count());
        foreach ($kategoris as $kategori) {
            $this->assertTrue($parameter->kategori->contains($kategori));
            $this->assertNotNull($parameter->kategori->find($kategori->id)->pivot->baku_mutu);
        }
    }

    #[Test]
    public function memastikan_relasi_dengan_subkategori_berfungsi()
    {
        $parameter = ParameterUji::factory()->create();
        $subkategori = SubKategori::factory()->create();
        
        $parameter->subkategori()->attach($subkategori->id, ['baku_mutu' => 15.5]);
        
        $this->assertTrue($parameter->subkategori->contains($subkategori));
        $this->assertEquals(15.5, $parameter->subkategori->first()->pivot->baku_mutu);
    }

    #[Test]
    public function memastikan_pivot_timestamps_berfungsi()
    {
        $parameter = ParameterUji::factory()->create();
        $kategori = Kategori::factory()->create();
        
        $parameter->kategori()->attach($kategori->id, ['baku_mutu' => 10.5]);
        
        $pivot = $parameter->kategori->first()->pivot;
        $this->assertNotNull($pivot->created_at);
        $this->assertNotNull($pivot->updated_at);
    }

    #[Test]
    public function memastikan_satuan_tidak_boleh_null()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        ParameterUji::factory()->create(['satuan' => null]);
    }

    #[Test]
    public function memastikan_bisa_update_nilai_pengujian()
    {
        $parameter = ParameterUji::factory()->create();
        $pengujian = Pengujian::factory()->create();
        
        $parameter->pengujian()->attach($pengujian->id, [
            'nilai' => 10.5,
            'keterangan' => 'Awal'
        ]);
        
        $parameter->pengujian()->updateExistingPivot($pengujian->id, [
            'nilai' => 15.5,
            'keterangan' => 'Updated'
        ]);
        
        $pivot = $parameter->pengujian->first()->pivot;
        $this->assertEquals(15.5, $pivot->nilai);
        $this->assertEquals('Updated', $pivot->keterangan);
    }

    #[Test]
    public function memastikan_harga_tidak_boleh_negatif()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Harga tidak boleh negatif');
        
        ParameterUji::factory()->create(['harga' => -1000]);
    }

    #[Test]
    public function memastikan_nama_parameter_bersifat_unique()
    {
        $namaParameter = 'pH Test Unique ' . uniqid();
        
        // Buat parameter pertama
        ParameterUji::factory()->create(['nama_parameter' => $namaParameter]);
        
        // Coba buat parameter dengan nama yang sama
        try {
            ParameterUji::factory()->create(['nama_parameter' => $namaParameter]);
            $this->fail('Seharusnya gagal membuat parameter dengan nama yang sama');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertTrue(true);
            $this->assertStringContainsString('Duplicate entry', $e->getMessage());
        }
    }

    #[Test]
    public function memastikan_bisa_update_data_parameter()
    {
        $parameter = ParameterUji::factory()->create();
        $dataUpdate = [
            'nama_parameter' => 'Parameter Updated',
            'satuan' => 'mg/L Updated',
            'harga' => 200000
        ];
        
        $parameter->update($dataUpdate);
        $parameter->refresh();
        
        $this->assertEquals($dataUpdate['nama_parameter'], $parameter->nama_parameter);
        $this->assertEquals($dataUpdate['satuan'], $parameter->satuan);
        $this->assertEquals($dataUpdate['harga'], $parameter->harga);
    }
}
