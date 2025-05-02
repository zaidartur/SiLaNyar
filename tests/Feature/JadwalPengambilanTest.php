<?php

namespace Tests\Feature;

use App\Models\FormPengajuan;  // Updated import
use App\Models\jadwal;
use App\Models\pegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalPengambilanTest extends TestCase
{
    use RefreshDatabase;

    private $pegawai;

    public function setUp(): void
    {
        parent::setUp();
        $this->pegawai = pegawai::factory()->create();
    }

    public function test_pegawai_dapat_melihat_daftar_jadwal()
    {
        $this->actingAs($this->pegawai, 'pegawai');
        $jadwal = jadwal::factory()->count(3)->create();

        $response = $this->get(route('pegawai.pengambilan.index'));

        $response->assertStatus(200)
                ->assertInertia(fn ($assert) => $assert
                    ->component('pegawai/jadwal/index')
                    ->has('jadwal', 3)
                );
    }

    public function test_pegawai_dapat_membuat_jadwal_baru()
    {
        $this->actingAs($this->pegawai, 'pegawai');
        $formPengajuan = FormPengajuan::factory()->create();

        $jadwalData = [
            'id_form_pengajuan' => $formPengajuan->id,
            'waktu_pengambilan' => now()->addDays(1)->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Pengambilan sampel'
        ];

        $response = $this->post(route('pegawai.pengambilan.store'), $jadwalData);

        $response->assertRedirect(route('pegawai.pengambilan.index'));
        $this->assertDatabaseHas('jadwal', $jadwalData);
    }

    public function test_pegawai_dapat_mengupdate_jadwal()
    {
        $this->actingAs($this->pegawai, 'pegawai');
        $jadwal = jadwal::factory()->create();

        $updatedData = [
            'waktu_pengambilan' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'selesai',
            'keterangan' => 'Pengambilan sampel selesai'
        ];

        $response = $this->put(route('pegawai.pengambilan.update', $jadwal), $updatedData);

        $response->assertRedirect(route('pegawai.pengambilan.index'));
        $this->assertDatabaseHas('jadwal', $updatedData);
    }

    public function test_pegawai_dapat_menghapus_jadwal()
    {
        $this->actingAs($this->pegawai, 'pegawai');
        $jadwal = jadwal::factory()->create();

        $response = $this->delete(route('pegawai.pengambilan.destroy', $jadwal->id));

        $response->assertRedirect(route('pegawai.pengambilan.index'));
        $this->assertDatabaseMissing('jadwal', ['id' => $jadwal->id]);
    }

    public function test_validasi_waktu_pengambilan_harus_di_masa_depan()
    {
        $this->actingAs($this->pegawai, 'pegawai');
        $formPengajuan = form_pengajuan::factory()->create();

        $response = $this->post(route('pegawai.pengambilan.store'), [
            'id_form_pengajuan' => $formPengajuan->id,
            'waktu_pengambilan' => now()->subDay()->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Pengambilan sampel'
        ]);

        $response->assertSessionHasErrors('waktu_pengambilan');
    }

    public function test_validasi_form_pengajuan_tidak_boleh_duplikat()
    {
        $this->actingAs($this->pegawai, 'pegawai');
        $formPengajuan = form_pengajuan::factory()->create();
        $jadwal = jadwal::factory()->create(['id_form_pengajuan' => $formPengajuan->id]);

        $response = $this->post(route('pegawai.pengambilan.store'), [
            'id_form_pengajuan' => $formPengajuan->id,
            'waktu_pengambilan' => now()->addDay()->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Pengambilan sampel'
        ]);

        $response->assertSessionHasErrors('id_form_pengajuan');
    }

    public function test_pegawai_dapat_melihat_detail_jadwal()
    {
        $this->actingAs($this->pegawai, 'pegawai');
        $jadwal = jadwal::factory()->create();

        $response = $this->get(route('pegawai.pengambilan.show', $jadwal));

        $response->assertStatus(200)
                ->assertInertia(fn ($assert) => $assert
                    ->component('jadwal/show')
                    ->has('jadwal')
                );
    }
}
