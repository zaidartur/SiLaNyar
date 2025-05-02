<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Jadwal;
use App\Models\FormPengajuan;
use App\Models\Pegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class JadwalPengambilanTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_membuat_jadwal_pengambilan()
    {
        $formPengajuan = FormPengajuan::factory()->create(['status_pengajuan' => 'diterima']);
        $pegawai = Pegawai::factory()->create(['status_verifikasi' => 'diterima']);

        $jadwalData = [
            'id_form_pengajuan' => $formPengajuan->id,
            'id_pegawai' => $pegawai->id,
            'waktu_pengambilan' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Pengambilan sampel air PDAM'
        ];

        $jadwal = Jadwal::create($jadwalData);

        $this->assertDatabaseHas('jadwal', $jadwalData);
        $this->assertEquals('diproses', $jadwal->status);
    }

    public function test_bisa_mengupdate_jadwal()
    {
        // Create jadwal with complete data including keterangan
        $jadwal = Jadwal::factory()->create([
            'status' => 'diproses',
            'keterangan' => 'Keterangan awal'
        ]);

        $dataUpdate = [
            'id_form_pengajuan' => $jadwal->id_form_pengajuan,
            'id_pegawai' => $jadwal->id_pegawai,
            'waktu_pengambilan' => now()->addDays(3)->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Jadwal diubah karena kendala teknis'
        ];

        $jadwal->update($dataUpdate);

        $this->assertDatabaseHas('jadwal', $dataUpdate);
    }

    public function test_tidak_bisa_mengupdate_jadwal_yang_sudah_selesai()
    {
        $jadwal = Jadwal::factory()->create([
            'status' => 'selesai',
            'keterangan' => 'Keterangan awal'
        ]);

        $waktuAwal = $jadwal->waktu_pengambilan;

        $dataUpdate = [
            'id_form_pengajuan' => $jadwal->id_form_pengajuan,
            'id_pegawai' => $jadwal->id_pegawai,
            'waktu_pengambilan' => now()->addDays(1)->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Keterangan baru'
        ];

        $jadwal->update($dataUpdate);
        $jadwal->refresh();

        // Status should remain 'selesai'
        $this->assertEquals('selesai', $jadwal->status);
        // But other fields can still be updated
        $this->assertEquals('Keterangan baru', $jadwal->keterangan);
    }

    public function test_bisa_menghapus_jadwal()
    {
        $jadwal = Jadwal::factory()->create([
            'keterangan' => 'Jadwal untuk dihapus'
        ]);
        
        $jadwalId = $jadwal->id;
        $jadwal->delete();

        $this->assertDatabaseMissing('jadwal', [
            'id' => $jadwalId
        ]);
    }

    public function test_relasi_dengan_form_pengajuan()
    {
        $formPengajuan = FormPengajuan::factory()->create(['status_pengajuan' => 'diterima']);
        $jadwal = Jadwal::factory()->create(['id_form_pengajuan' => $formPengajuan->id]);

        $this->assertEquals($formPengajuan->id, $jadwal->form_pengajuan->id);
    }

    public function test_relasi_dengan_pegawai()
    {
        $pegawai = Pegawai::factory()->create(['status_verifikasi' => 'diterima']);
        $jadwal = Jadwal::factory()->create(['id_pegawai' => $pegawai->id]);

        $this->assertEquals($pegawai->id, $jadwal->pegawai->id);
    }

    public function test_validasi_waktu_pengambilan_harus_masa_depan()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Jadwal::create([
            'id_form_pengajuan' => FormPengajuan::factory()->create()->id,
            'id_pegawai' => Pegawai::factory()->create()->id,
            'waktu_pengambilan' => now()->subDay()->format('Y-m-d'),
            'status' => 'diproses'
        ]);
    }

    public function test_bisa_memfilter_jadwal_berdasarkan_status()
    {
        Jadwal::factory()->count(3)->create(['status' => 'diproses']);
        Jadwal::factory()->count(2)->create(['status' => 'selesai']);

        $jadwalDiproses = Jadwal::where('status', 'diproses')->get();
        $jadwalSelesai = Jadwal::where('status', 'selesai')->get();

        $this->assertEquals(3, $jadwalDiproses->count());
        $this->assertEquals(2, $jadwalSelesai->count());
    }

    public function test_bisa_memfilter_jadwal_berdasarkan_tanggal()
    {
        $tanggal = now()->addDays(5)->format('Y-m-d');
        Jadwal::factory()->create(['waktu_pengambilan' => $tanggal]);

        $jadwalFiltered = Jadwal::whereDate('waktu_pengambilan', $tanggal)->get();

        $this->assertEquals(1, $jadwalFiltered->count());
    }

    public function test_validasi_form_pengajuan_harus_ada()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Jadwal::create([
            'id_form_pengajuan' => 999999, // ID yang tidak ada
            'waktu_pengambilan' => now()->addDay(),
            'status' => 'diproses'
        ]);
    }
}
