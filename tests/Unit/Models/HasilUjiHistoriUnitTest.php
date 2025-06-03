<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\HasilUji;
use App\Models\HasilUjiHistori;
use App\Models\Pengujian;
use App\Models\FormPengajuan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class HasilUjiHistoriUnitTest extends TestCase
{
    use RefreshDatabase;

    private function buatHasilUjiLangsung($id = null): int
    {
        // Buat FormPengajuan terlebih dahulu
        $formPengajuan = FormPengajuan::factory()->create();
        
        // Buat Pengujian yang terkait dengan FormPengajuan
        $pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id
        ]);
        
        // Membuat HasilUji langsung melalui query builder untuk menghindari timestamps
        $hasilUjiId = $id ?? rand(1000, 9999);
        DB::table('hasil_uji')->insert([
            'id' => $hasilUjiId,
            'kode_hasil_uji' => 'HU-' . date('my') . '-001',
            'id_pengujian' => $pengujian->id, // Gunakan ID pengujian yang valid
            'status' => 'draf',
            'proses_review_at' => now(),
        ]);
        
        return $hasilUjiId;
    }

    #[Test]
    public function memastikan_relasi_dengan_hasil_uji_berfungsi()
    {
        // Buat HasilUji langsung dengan query builder
        $hasilUjiId = $this->buatHasilUjiLangsung();
        
        // Pastikan HasilUji ada di database
        $this->assertDatabaseHas('hasil_uji', ['id' => $hasilUjiId]);
        
        // Buat histori yang merujuk ke HasilUji
        $histori = HasilUjiHistori::create([
            'id_hasil_uji' => $hasilUjiId,
            'data_parameterdanpengujian' => ['test' => 'data'],
            'status' => 'draf',
            'diupdate_oleh' => 'Tester'
        ]);
        
        // Test relasi dengan eager loading
        $historiDenganRelasi = HasilUjiHistori::with('hasil_uji')->find($histori->id);
        $this->assertEquals($hasilUjiId, $historiDenganRelasi->hasil_uji->id);
    }

    #[Test]
    public function memastikan_relasi_dengan_user_melalui_diupdate_oleh()
    {
        $this->markTestSkipped(
            'Relasi user tidak dapat diuji karena tidak ada kolom id_user di tabel hasil_uji_histori'
        );
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $histori = new HasilUjiHistori;
        
        // Sesuaikan dengan fillable sebenarnya pada model
        $fillable = [
            'id_hasil_uji',
            'data_parameterdanpengujian',
            'status',
            'diupdate_oleh'
        ];
        
        $this->assertEquals($fillable, $histori->getFillable());
    }

    #[Test]
    public function memastikan_data_parameter_dan_pengujian_disimpan_sebagai_array()
    {
        // Buat HasilUji langsung
        $hasilUjiId = $this->buatHasilUjiLangsung();
        
        $data = [
            'parameter' => [
                [
                    'id' => 1,
                    'nama' => 'Parameter Test',
                    'nilai' => 12.34,
                ]
            ]
        ];
        
        $histori = HasilUjiHistori::create([
            'id_hasil_uji' => $hasilUjiId,
            'data_parameterdanpengujian' => $data,
            'status' => 'draf',
            'diupdate_oleh' => 'Tester'
        ]);
        
        // Ambil ulang dari database
        $direfresh = HasilUjiHistori::find($histori->id);
        
        $this->assertIsArray($direfresh->data_parameterdanpengujian);
        $this->assertEquals($data, $direfresh->data_parameterdanpengujian);
    }

    #[Test]
    public function memastikan_status_bisa_diubah()
    {
        // Buat HasilUji langsung
        $hasilUjiId = $this->buatHasilUjiLangsung();
        
        $histori = HasilUjiHistori::create([
            'id_hasil_uji' => $hasilUjiId,
            'data_parameterdanpengujian' => ['test' => 'data'],
            'status' => 'draf',
            'diupdate_oleh' => 'Tester'
        ]);
        
        $historiId = $histori->id;
        
        // Update status
        HasilUjiHistori::where('id', $historiId)
            ->update(['status' => 'selesai']);
        
        // Ambil ulang dari database
        $direfresh = HasilUjiHistori::find($historiId);
        
        $this->assertEquals('selesai', $direfresh->status);
    }

    #[Test]
    public function memastikan_diupdate_oleh_tersimpan_dengan_benar()
    {
        // Buat HasilUji langsung
        $hasilUjiId = $this->buatHasilUjiLangsung();
        
        $nama = 'Penguji Utama';
        
        $histori = HasilUjiHistori::create([
            'id_hasil_uji' => $hasilUjiId,
            'data_parameterdanpengujian' => ['test' => 'data'],
            'status' => 'draf',
            'diupdate_oleh' => $nama
        ]);
        
        // Ambil ulang dari database
        $direfresh = HasilUjiHistori::find($histori->id);
        
        $this->assertEquals($nama, $direfresh->diupdate_oleh);
    }

    #[Test]
    public function memastikan_nama_tabel_benar()
    {
        $histori = new HasilUjiHistori;
        
        $this->assertEquals('hasil_uji_histori', $histori->getTable());
    }

    #[Test]
    public function memastikan_casts_bekerja_dengan_benar()
    {
        $histori = new HasilUjiHistori;
        $casts = $histori->getCasts();
        
        // Pastikan 'data_parameterdanpengujian' di-cast ke 'array'
        $this->assertArrayHasKey('data_parameterdanpengujian', $casts);
        $this->assertEquals('array', $casts['data_parameterdanpengujian']);
    }
}
