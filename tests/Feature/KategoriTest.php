<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Kategori;
use App\Models\ParameterUji;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KategoriTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_membuat_kategori_baru()
    {
        $kategoriData = [
            'nama' => 'Air Limbah Industri',
            'harga' => 500000
        ];

        $kategori = Kategori::create($kategoriData);

        $this->assertDatabaseHas('kategori', $kategoriData);
        $this->assertEquals('Air Limbah Industri', $kategori->nama);
        $this->assertEquals(500000, $kategori->harga);
    }

    public function test_bisa_mengupdate_kategori()
    {
        $kategori = Kategori::factory()->create();

        $dataUpdate = [
            'nama' => 'Air PDAM Updated',
            'harga' => 600000
        ];

        $kategori->update($dataUpdate);

        $this->assertDatabaseHas('kategori', $dataUpdate);
    }

    public function test_bisa_menghapus_kategori()
    {
        $kategori = Kategori::factory()->create();
        
        $kategori->delete();

        $this->assertDatabaseMissing('kategori', [
            'id' => $kategori->id
        ]);
    }

    public function test_relasi_dengan_parameter_uji()
    {
        $kategori = Kategori::factory()->create();
        $parameter = ParameterUji::factory()->create();

        $kategori->parameter()->attach($parameter->id);

        $this->assertTrue($kategori->parameter->contains($parameter));
        $this->assertEquals(1, $kategori->parameter->count());
    }

    public function test_validasi_nama_kategori_tidak_boleh_kosong()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Kategori::create([
            'nama' => null,
            'harga' => 500000
        ]);
    }

    public function test_validasi_harga_harus_numerik()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Kategori::create([
            'nama' => 'Air Minum',
            'harga' => 'bukan-angka'
        ]);
    }

    public function test_bisa_mendapatkan_daftar_kategori()
    {
        $jumlahKategori = 5;
        Kategori::factory()->count($jumlahKategori)->create();

        $kategoriList = Kategori::all();

        $this->assertEquals($jumlahKategori, $kategoriList->count());
    }

    public function test_bisa_mencari_kategori_berdasarkan_nama()
    {
        $kategori = Kategori::factory()->create([
            'nama' => 'Air Minum Kemasan'
        ]);

        $hasilPencarian = Kategori::where('nama', 'Air Minum Kemasan')->first();

        $this->assertEquals($kategori->id, $hasilPencarian->id);
    }
}
