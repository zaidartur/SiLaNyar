<?php

namespace Tests\Feature;

use App\Models\pengujian;
use App\Models\form_pengajuan;
use App\Models\pegawai;
use App\Models\kategori;
use App\Models\parameter_uji;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class PengujianTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $form_pengajuan;
    private $pegawai;
    private $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->form_pengajuan = form_pengajuan::factory()->create();
        $this->pegawai = pegawai::factory()->create();
        $this->kategori = kategori::factory()->create();
    }

    public function test_can_display_pengujian_list(): void
    {
        pengujian::factory()->count(3)->create();

        $response = $this->get(route('pengujian.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('pengujian/index')
            ->has('pengujian', 3)
        );
    }

    public function test_can_create_new_pengujian(): void
    {
        $jamMulai = now();
        $jamSelesai = $jamMulai->copy()->addHour();
        
        $pengujianData = [
            'id_form_pengajuan' => $this->form_pengajuan->id,
            'id_pegawai' => $this->pegawai->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_uji' => now()->format('Y-m-d'),
            'jam_mulai' => $jamMulai->format('H:i'),
            'jam_selesai' => $jamSelesai->format('H:i'),
            'status' => 'diproses'
        ];

        $response = $this->post('/pengujian/store', $pengujianData);

        $response->assertRedirect('/pengujian');
        $this->assertDatabaseHas('pengujian', $pengujianData);
    }

    public function test_can_show_edit_pengujian_form(): void
    {
        $pengujian = pengujian::factory()->create();

        $response = $this->get("/pengujian/edit/{$pengujian->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('pengujian/edit')
            ->has('pengujian')
        );
    }

    public function test_can_update_pengujian(): void
    {
        $pengujian = pengujian::factory()->create();
        $jamMulai = now();
        $jamSelesai = $jamMulai->copy()->addHour();
        $updatedData = [
            'id_form_pengajuan' => $this->form_pengajuan->id,
            'id_pegawai' => $this->pegawai->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_uji' => now()->addDay()->format('Y-m-d'),
            'jam_mulai' => $jamMulai->format('H:i'),
            'jam_selesai' => $jamSelesai->format('H:i'),
            'status' => 'selesai'
        ];

        $response = $this->put("/pengujian/{$pengujian->id}/edit", $updatedData);

        $response->assertRedirect('/pengujian');
        $this->assertDatabaseHas('pengujian', $updatedData);
    }

    public function test_can_delete_pengujian(): void
    {
        $pengujian = pengujian::factory()->create();

        $response = $this->delete("/pengujian/{$pengujian->id}");

        $response->assertRedirect(route('pengujian.index'));
        $this->assertDatabaseMissing('pengujian', ['id' => $pengujian->id]);
    }

    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->post('/pengujian/store', []);

        $response->assertSessionHasErrors([
            'id_form_pengajuan',
            'id_pegawai',
            'id_kategori',
            'tanggal_uji',
            'jam_mulai',
            'jam_selesai'
        ]);
    }

    public function test_can_show_pengujian_details(): void
    {
        $pengujian = pengujian::factory()->create();
        $parameters = parameter_uji::factory()->count(2)->create();
        
        // Attach parameters with hasil_uji pivot data
        foreach($parameters as $parameter) {
            $pengujian->parameter_uji()->attach($parameter->id, [
                'nilai' => $this->faker->randomFloat(2, 0, 100),
                'keterangan' => $this->faker->sentence
            ]);
        }

        $response = $this->get("/pengujian/{$pengujian->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('pengujian/show')
            ->has('pengujian.parameter_uji', 2)
            ->has('pengujian.form_pengajuan')
            ->has('pengujian.pegawai')
            ->has('pengujian.kategori')
        );
    }
    
    public function test_validates_valid_foreign_keys(): void
    {
        $pengujianData = [
            'id_form_pengajuan' => 999999,
            'id_pegawai' => 999999,
            'id_kategori' => 999999,
            'tanggal_uji' => now()->format('Y-m-d'),
            'jam_mulai' => now()->format('Y-m-d'),
            'jam_selesai' => now()->format('Y-m-d'),
            'status' => 'diproses'
        ];

        $response = $this->post('/pengujian/store', $pengujianData);

        $response->assertSessionHasErrors([
            'id_form_pengajuan',
            'id_pegawai',
            'id_kategori'
        ]);
    }
    
    public function test_validates_valid_status(): void
    {
        $pengujian = pengujian::factory()->create();

        $response = $this->put("/pengujian/{$pengujian->id}/edit", [
            'status' => 'invalid_status'
        ]);

        $response->assertSessionHasErrors('status');
    }
    
    public function test_can_attach_hasil_uji(): void
    {
        $pengujian = pengujian::factory()->create();
        $parameter = parameter_uji::factory()->create();

        $hasilUjiData = [
            'nilai' => $this->faker->randomFloat(2, 0, 100),
            'keterangan' => $this->faker->sentence
        ];

        $pengujian->parameter_uji()->attach($parameter->id, $hasilUjiData);

        $this->assertDatabaseHas('hasil_uji', [
            'id_pengujian' => $pengujian->id,
            'id_parameter' => $parameter->id,
            'nilai' => $hasilUjiData['nilai'],
            'keterangan' => $hasilUjiData['keterangan']
        ]);
    }
    
    public function test_deleting_pengujian_removes_hasil_uji(): void
    {
        $pengujian = pengujian::factory()->create();
        $parameter = parameter_uji::factory()->create();

        $pengujian->parameter_uji()->attach($parameter->id, [
            'nilai' => $this->faker->randomFloat(2, 0, 100),
            'keterangan' => $this->faker->sentence
        ]);

        $pengujian->delete();

        $this->assertDatabaseMissing('hasil_uji', [
            'id_pengujian' => $pengujian->id
        ]);
    }
}
