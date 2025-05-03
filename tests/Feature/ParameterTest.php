<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ParameterUji;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParameterTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_membuat_parameter_baru()
    {
        $parameterData = [
            'nama_parameter' => 'pH',
            'satuan' => 'pH',
            'baku_mutu' => 7.0,
            'harga' => 100000
        ];

        $parameter = ParameterUji::create($parameterData);

        $this->assertDatabaseHas('parameter_uji', $parameterData);
        $this->assertEquals('pH', $parameter->nama_parameter);
        $this->assertEquals(100000, $parameter->harga);
    }

    public function test_bisa_mengupdate_parameter()
    {
        $parameter = ParameterUji::factory()->create();

        $dataUpdate = [
            'nama_parameter' => 'BOD Updated',
            'satuan' => 'mg/L',
            'baku_mutu' => 35.0,
            'harga' => 175000
        ];

        $parameter->update($dataUpdate);

        $this->assertDatabaseHas('parameter_uji', $dataUpdate);
    }

    public function test_bisa_menghapus_parameter()
    {
        $parameter = ParameterUji::factory()->create();
        
        $parameter->delete();

        $this->assertDatabaseMissing('parameter_uji', [
            'id' => $parameter->id
        ]);
    }

    public function test_relasi_dengan_kategori()
    {
        $parameter = ParameterUji::factory()->create();
        $kategori = Kategori::factory()->create();

        $parameter->kategori()->attach($kategori->id);

        $this->assertTrue($parameter->kategori->contains($kategori));
        $this->assertEquals(1, $parameter->kategori->count());
    }

    public function test_validasi_nama_parameter_tidak_boleh_kosong()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        ParameterUji::create([
            'nama_parameter' => null,
            'satuan' => 'mg/L',
            'baku_mutu' => 30.0,
            'harga' => 150000
        ]);
    }

    public function test_validasi_harga_harus_numerik()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        ParameterUji::create([
            'nama_parameter' => 'TSS',
            'satuan' => 'mg/L',
            'baku_mutu' => 50.0,
            'harga' => 'bukan-angka'
        ]);
    }

    public function test_bisa_mendapatkan_daftar_parameter()
    {
        $jumlahParameter = 5;
        ParameterUji::factory()->count($jumlahParameter)->create();

        $parameterList = ParameterUji::all();

        $this->assertEquals($jumlahParameter, $parameterList->count());
    }

    public function test_bisa_mencari_parameter_berdasarkan_nama()
    {
        $parameter = ParameterUji::factory()->create([
            'nama_parameter' => 'Kekeruhan'
        ]);

        $hasilPencarian = ParameterUji::where('nama_parameter', 'Kekeruhan')->first();

        $this->assertEquals($parameter->id, $hasilPencarian->id);
    }

    public function test_validasi_baku_mutu_harus_numerik()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        ParameterUji::create([
            'nama_parameter' => 'DO',
            'satuan' => 'mg/L',
            'baku_mutu' => 'invalid',
            'harga' => 150000
        ]);
    }

    public function test_parameter_memiliki_satuan_yang_valid()
    {
        $parameter = ParameterUji::factory()->create([
            'satuan' => 'mg/L'
        ]);

        $this->assertNotEmpty($parameter->satuan);
        $this->assertEquals('mg/L', $parameter->satuan);
    }
}
