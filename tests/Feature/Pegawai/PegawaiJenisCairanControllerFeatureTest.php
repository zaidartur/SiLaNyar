<?php

namespace Tests\Feature\Pegawai;

use App\Models\JenisCairan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PegawaiJenisCairanControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected Permission $kelolaJenisCairanPermission;

    public function setUp(): void
    {
        parent::setUp();

        // Configure Vite for testing
        config(['app.asset_url' => null]);

        // Create permission and role with required kode_permission field
        $this->kelolaJenisCairanPermission = Permission::firstOrCreate([
            'name' => 'kelola jenis cairan',
            'guard_name' => 'web'
        ], [
            'kode_permission' => 'P-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)
        ]);

        $pegawaiRole = Role::firstOrCreate(
            ['name' => 'pegawai', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002']
        );

        $pegawaiRole->givePermissionTo($this->kelolaJenisCairanPermission);

        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);
    }

    public function test_index_menampilkan_daftar_jenis_cairan()
    {
        $jenisCairan1 = JenisCairan::factory()->create([
            'nama' => 'Air Limbah Industri',
            'batas_minimum' => 100.5,
            'batas_maksimum' => 1000
        ]);

        $jenisCairan2 = JenisCairan::factory()->create([
            'nama' => 'Air Sumur Bor',
            'batas_minimum' => 50,
            'batas_maksimum' => 500
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.jenis_cairan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/jenis_cairan/Index')
                ->has('jenis_cairan', 2)
                ->has('jenis_cairan.0', fn (Assert $jenisCairan) => $jenisCairan
                    ->where('id', $jenisCairan1->id)
                    ->where('nama', 'Air Limbah Industri')
                    ->where('batas_minimum', 100.5)
                    ->where('batas_maksimum', 1000)
                    ->has('kode_jenis_cairan')
                    ->etc()
                )
                ->has('jenis_cairan.1', fn (Assert $jenisCairan) => $jenisCairan
                    ->where('id', $jenisCairan2->id)
                    ->where('nama', 'Air Sumur Bor')
                    ->where('batas_minimum', 50)
                    ->where('batas_maksimum', 500)
                    ->etc()
                )
            );
    }

    public function test_index_menampilkan_halaman_kosong_jika_tidak_ada_data()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.jenis_cairan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/jenis_cairan/Index')
                ->has('jenis_cairan', 0)
            );
    }

    public function test_create_menampilkan_form_tambah_jenis_cairan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get('/pegawai/jenis-cairan/create');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/jenis_cairan/Tambah')
            );
    }

    public function test_store_membuat_jenis_cairan_baru_dengan_data_valid()
    {
        $data = [
            'nama' => 'Air Limbah Domestik',
            'batas_minimum' => 75.5,
            'batas_maksimum' => 750.0
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/jenis-cairan/store', $data);

        $response->assertRedirect(route('pegawai.jenis_cairan.index'))
            ->assertSessionHas('message', 'Jenis Cairan Berhasil Ditambahkan');

        $this->assertDatabaseHas('jenis_cairan', [
            'nama' => 'Air Limbah Domestik',
            'batas_minimum' => 75.5,
            'batas_maksimum' => 750.0
        ]);

        $jenisCairan = JenisCairan::where('nama', 'Air Limbah Domestik')->first();
        $this->assertNotNull($jenisCairan->kode_jenis_cairan);
        $this->assertStringStartsWith('JC-', $jenisCairan->kode_jenis_cairan);
    }

    public function test_store_membuat_jenis_cairan_tanpa_batas_maksimum()
    {
        $data = [
            'nama' => 'Air Artesis',
            'batas_minimum' => 25.0
            // batas_maksimum tidak diisi (nullable)
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/jenis-cairan/store', $data);

        $response->assertRedirect(route('pegawai.jenis_cairan.index'));

        $this->assertDatabaseHas('jenis_cairan', [
            'nama' => 'Air Artesis',
            'batas_minimum' => 25.0,
            'batas_maksimum' => null
        ]);
    }

    public function test_store_menolak_data_dengan_batas_minimum_lebih_besar_dari_maksimum()
    {
        $data = [
            'nama' => 'Air Test',
            'batas_minimum' => 1000.0,
            'batas_maksimum' => 500.0 // Invalid: maksimum < minimum
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/jenis-cairan/store', $data);

        $response->assertSessionHasErrors(['batas_maksimum']);

        $this->assertDatabaseMissing('jenis_cairan', [
            'nama' => 'Air Test'
        ]);
    }

    public function test_store_memvalidasi_field_yang_diperlukan()
    {
        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/jenis-cairan/store', []);

        $response->assertSessionHasErrors(['nama', 'batas_minimum']);
    }

    public function test_edit_menampilkan_form_edit_dengan_data_jenis_cairan()
    {
        $jenisCairan = JenisCairan::factory()->create([
            'nama' => 'Air Tanah Dalam',
            'batas_minimum' => 100.0,
            'batas_maksimum' => 2000.0
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.jenis_cairan.edit', $jenisCairan));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/jenis_cairan/Edit')
                ->has('jenis_cairan', fn (Assert $jenisCairanData) => $jenisCairanData
                    ->where('id', $jenisCairan->id)
                    ->where('nama', 'Air Tanah Dalam')
                    ->where('batas_minimum', 100)
                    ->where('batas_maksimum', 2000)
                    ->has('kode_jenis_cairan')
                    ->etc()
                )
            );
    }

    public function test_update_memodifikasi_jenis_cairan_dengan_data_valid()
    {
        $jenisCairan = JenisCairan::factory()->create([
            'nama' => 'Air Lama',
            'batas_minimum' => 50.0,
            'batas_maksimum' => 500.0
        ]);

        $dataUpdate = [
            'nama' => 'Air Baru Updated',
            'batas_minimum' => 80.0,
            'batas_maksimum' => 800.0
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/jenis-cairan/{$jenisCairan->id}/edit", $dataUpdate);

        $response->assertRedirect(route('pegawai.jenis_cairan.index'))
            ->assertSessionHas('message', 'Jenis Cairan Berhasil Diedit!');

        $this->assertDatabaseHas('jenis_cairan', [
            'id' => $jenisCairan->id,
            'nama' => 'Air Baru Updated',
            'batas_minimum' => 80.0,
            'batas_maksimum' => 800.0
        ]);
    }

    public function test_update_menolak_perubahan_dengan_batas_tidak_valid()
    {
        $jenisCairan = JenisCairan::factory()->create();

        $dataUpdate = [
            'nama' => 'Air Updated',
            'batas_minimum' => 1000.0,
            'batas_maksimum' => 100.0 // Invalid
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/jenis-cairan/{$jenisCairan->id}/edit", $dataUpdate);

        $response->assertSessionHasErrors(['batas_maksimum']);
    }

    public function test_destroy_menghapus_jenis_cairan()
    {
        $jenisCairan = JenisCairan::factory()->create([
            'nama' => 'Air Akan Dihapus'
        ]);

        $response = $this->actingAs($this->pegawai)
            ->delete("/pegawai/jenis-cairan/{$jenisCairan->id}");

        $response->assertRedirect(route('pegawai.jenis_cairan.index'))
            ->assertSessionHas('message', 'Jenis Cairan Berhasil Dihapus!');

        $this->assertDatabaseMissing('jenis_cairan', [
            'id' => $jenisCairan->id
        ]);
    }

    public function test_destroy_mengembalikan_404_untuk_id_tidak_ada()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete('/pegawai/jenis-cairan/99999');

        $response->assertStatus(404);
    }

    public function test_akses_ditolak_tanpa_permission_kelola_jenis_cairan()
    {
        $userTanpaPermission = User::factory()->create();

        $response = $this->actingAs($userTanpaPermission)
            ->get(route('pegawai.jenis_cairan.index'));

        $response->assertStatus(403);
    }

    public function test_akses_ditolak_untuk_user_tidak_login()
    {
        $response = $this->get(route('pegawai.jenis_cairan.index'));

        $response->assertStatus(403);
    }

    public function test_kode_jenis_cairan_auto_generate_berurutan()
    {
        $jenisCairan1 = JenisCairan::factory()->create(['nama' => 'Test 1']);
        $jenisCairan2 = JenisCairan::factory()->create(['nama' => 'Test 2']);

        $this->assertStringStartsWith('JC-', $jenisCairan1->kode_jenis_cairan);
        $this->assertStringStartsWith('JC-', $jenisCairan2->kode_jenis_cairan);

        // Extract numbers and verify they are sequential
        $num1 = (int)substr($jenisCairan1->kode_jenis_cairan, -3);
        $num2 = (int)substr($jenisCairan2->kode_jenis_cairan, -3);

        $this->assertEquals($num1 + 1, $num2);
    }

    public function test_validasi_batas_maksimum_after_or_equal_batas_minimum()
    {
        $data = [
            'nama' => 'Air Test Equal',
            'batas_minimum' => 100.0,
            'batas_maksimum' => 150.0 // Valid: maksimum > minimum
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/jenis-cairan/store', $data);

        $response->assertRedirect(route('pegawai.jenis_cairan.index'));

        $this->assertDatabaseHas('jenis_cairan', [
            'nama' => 'Air Test Equal',
            'batas_minimum' => 100,
            'batas_maksimum' => 150
        ]);
    }
}
