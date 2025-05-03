<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pengujian;
use App\Models\FormPengajuan;
use App\Models\Pegawai;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PengujianTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_membuat_pengujian_baru()
    {
        $formPengajuan = FormPengajuan::factory()->create(['status_pengajuan' => 'diterima']);
        $pegawai = Pegawai::factory()->create(['status_verifikasi' => 'diterima']);
        $kategori = Kategori::factory()->create();

        $dataPengujian = [
            'id_form_pengajuan' => $formPengajuan->id,
            'id_pegawai' => $pegawai->id,
            'id_kategori' => $kategori->id,
            'tanggal_uji' => now()->addDays(2)->format('Y-m-d'),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'status' => 'diproses'
        ];

        $pengujian = Pengujian::create($dataPengujian);

        $this->assertDatabaseHas('pengujian', $dataPengujian);
        $this->assertEquals('diproses', $pengujian->status);
    }

    public function test_bisa_mengupdate_pengujian()
    {
        $pengujian = Pengujian::factory()->create(['status' => 'diproses']);

        $dataUpdate = [
            'id_form_pengajuan' => $pengujian->id_form_pengajuan,
            'id_pegawai' => $pengujian->id_pegawai,
            'id_kategori' => $pengujian->id_kategori,
            'tanggal_uji' => now()->addDays(3)->format('Y-m-d'),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'diproses'
        ];

        $pengujian->update($dataUpdate);
        $pengujian->refresh();

        $this->assertEquals($dataUpdate['jam_mulai'], $pengujian->jam_mulai);
        $this->assertEquals($dataUpdate['jam_selesai'], $pengujian->jam_selesai);
    }

    public function test_tidak_bisa_mengupdate_pengujian_yang_sudah_selesai()
    {
        $pengujian = Pengujian::factory()->create([
            'status' => 'selesai',
            'tanggal_uji' => now()->format('Y-m-d'),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00'
        ]);

        $dataUpdate = [
            'id_form_pengajuan' => $pengujian->id_form_pengajuan,
            'id_pegawai' => $pengujian->id_pegawai,
            'id_kategori' => $pengujian->id_kategori,
            'tanggal_uji' => now()->addDay()->format('Y-m-d'),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'diproses'
        ];

        $pengujian->update($dataUpdate);
        $pengujian->refresh();

        // Status seharusnya tetap 'selesai'
        $this->assertEquals('selesai', $pengujian->status);
        // Tapi field lain bisa berubah
        $this->assertEquals($dataUpdate['jam_mulai'], $pengujian->jam_mulai);
    }

    public function test_bisa_menghapus_pengujian()
    {
        $pengujian = Pengujian::factory()->create();
        
        $pengujianId = $pengujian->id;
        $pengujian->delete();

        $this->assertDatabaseMissing('pengujian', [
            'id' => $pengujianId
        ]);
    }

    public function test_relasi_dengan_form_pengajuan()
    {
        $formPengajuan = FormPengajuan::factory()->create(['status_pengajuan' => 'diterima']);
        $pengujian = Pengujian::factory()->create(['id_form_pengajuan' => $formPengajuan->id]);

        $this->assertEquals($formPengajuan->id, $pengujian->form_pengajuan->id);
    }

    public function test_relasi_dengan_pegawai()
    {
        $pegawai = Pegawai::factory()->create(['status_verifikasi' => 'diterima']);
        $pengujian = Pengujian::factory()->create(['id_pegawai' => $pegawai->id]);

        $this->assertEquals($pegawai->id, $pengujian->pegawai->id);
    }

    public function test_validasi_jam_selesai_harus_setelah_jam_mulai()
    {
        $pengujian = Pengujian::factory()->create([
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00'
        ]);

        $this->expectException(ValidationException::class);

        // Mencoba update dengan jam selesai yang lebih awal dari jam mulai
        $pengujian->update([
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '09:00:00'
        ]);
    }

    public function test_bisa_memfilter_pengujian_berdasarkan_status()
    {
        Pengujian::factory()->count(3)->create(['status' => 'diproses']);
        Pengujian::factory()->count(2)->create(['status' => 'selesai']);

        $pengujianDiproses = Pengujian::where('status', 'diproses')->get();
        $pengujianSelesai = Pengujian::where('status', 'selesai')->get();

        $this->assertEquals(3, $pengujianDiproses->count());
        $this->assertEquals(2, $pengujianSelesai->count());
    }

    public function test_bisa_memfilter_pengujian_berdasarkan_tanggal()
    {
        $tanggal = now()->addDays(5)->format('Y-m-d');
        Pengujian::factory()->create(['tanggal_uji' => $tanggal]);

        $pengujianFiltered = Pengujian::whereDate('tanggal_uji', $tanggal)->get();

        $this->assertEquals(1, $pengujianFiltered->count());
    }
}
