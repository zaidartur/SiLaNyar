<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PegawaiPengajuanControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected FormPengajuan $pengajuanDiantar;
    protected FormPengajuan $pengajuanDiambil;
    protected Kategori $kategori;
    protected ParameterUji $parameter;
    protected Instansi $instansi;
    protected JenisCairan $jenisCairan;

    public function setUp(): void
    {
        parent::setUp();

        // Konfigurasi Vite untuk testing
        config(['app.asset_url' => null]);

        // Buat role dengan kode_role
        $pegawaiRole = Role::firstOrCreate(
            ['name' => 'pegawai', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002']
        );
        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        // Buat permission dengan kode_permission
        $permissions = [
            ['name' => 'lihat pengajuan', 'kode' => 'PS-001'],
            ['name' => 'detail pengajuan', 'kode' => 'PS-002'],
            ['name' => 'edit pengajuan', 'kode' => 'PS-003'],
            ['name' => 'hapus pengajuan', 'kode' => 'PS-004'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['kode_permission' => $permission['kode']]
            );
        }

        // Berikan permission kepada role pegawai
        $pegawaiRole->givePermissionTo(array_column($permissions, 'name'));

        // Buat user
        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        // Buat data pendukung
        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $this->jenisCairan = JenisCairan::factory()->create();
        $this->parameter = ParameterUji::factory()->create();
        $this->kategori = Kategori::factory()->create();
        $this->kategori->parameter()->attach($this->parameter->id, ['baku_mutu' => 'Test Baku Mutu']);

        // Buat pengajuan dengan metode diantar
        $this->pengajuanDiantar = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'metode_pengambilan' => 'diantar',
            'status_pengajuan' => 'proses_validasi',
            'lokasi' => null
        ]);
        $this->pengajuanDiantar->parameter()->attach($this->parameter->id);

        // Buat pengajuan dengan metode diambil
        $this->pengajuanDiambil = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima',
            'lokasi' => 'Jl. Test No. 123'
        ]);
        $this->pengajuanDiambil->parameter()->attach($this->parameter->id);
    }

    public function test_index_menampilkan_daftar_pengajuan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengajuan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengajuan/Index')
                ->has('pengajuan', 2)
                ->has('filter')
            );
    }

    public function test_show_menampilkan_detail_pengajuan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengajuan.detail', $this->pengajuanDiantar->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengajuan/Detail')
                ->has('pengajuan', fn (Assert $pengajuan) => $pengajuan
                    ->where('id', $this->pengajuanDiantar->id)
                    ->etc()
                )
            );
    }

    public function test_edit_menampilkan_form_edit_pengajuan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengajuan.edit', $this->pengajuanDiantar));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengajuan/Edit')
                ->has('pengajuan')
                ->has('kategoriList')
                ->has('parameterList')
            );
    }

    public function test_update_pengajuan_diambil_diterima_berhasil()
    {
        $data = [
            'status_pengajuan' => 'diterima'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.pengajuan.update', $this->pengajuanDiambil->id), $data);

        $response->assertRedirect(route('pegawai.pengajuan.index'))
            ->assertSessionHas('message', 'Pengajuan Telah Diterima!');

        $this->assertDatabaseHas('form_pengajuan', [
            'id' => $this->pengajuanDiambil->id,
            'status_pengajuan' => 'diterima'
        ]);
    }

    // public function test_update_pengajuan_diantar_error_karena_bug()
    // {
    //     $data = [
    //         'status_pengajuan' => 'diterima',
    //         'id_kategori' => $this->kategori->id,
    //         'parameter' => [$this->parameter->id],
    //         'metode_pembayaran' => 'transfer'
    //     ];

    //     $response = $this->actingAs($this->pegawai)
    //         ->put(route('pegawai.pengajuan.update', $this->pengajuanDiantar->id), $data);

    //     // Controller akan error karena Pembayaran::createOrUpdate tidak ada
    //     $response->assertRedirect()
    //         ->assertSessionHas('error');
    // }

    // public function test_update_pengajuan_ditolak_error_karena_bug()
    // {
    //     $data = [
    //         'status_pengajuan' => 'ditolak'
    //     ];

    //     $response = $this->actingAs($this->pegawai)
    //         ->put(route('pegawai.pengajuan.update', $this->pengajuanDiantar->id), $data);

    //     // Controller akan error karena mengakses $validated['id_kategori'] yang tidak ada
    //     $response->assertRedirect()
    //         ->assertSessionHas('error');
    // }

    public function test_destroy_pengajuan_berhasil()
    {
        $this->pengajuanDiantar->update(['status_pengajuan' => 'diterima']);

        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.pengajuan.destroy', $this->pengajuanDiantar));

        $response->assertRedirect()
            ->assertSessionHas('message', 'Pengajuan Berhasil Dihapus');

        $this->assertDatabaseMissing('form_pengajuan', [
            'id' => $this->pengajuanDiantar->id
        ]);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.pengajuan.index'));

        $response->assertStatus(403);
    }

    public function test_show_pengajuan_tidak_ditemukan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengajuan.detail', 99999));

        $response->assertStatus(404);
    }

    public function test_validasi_status_pengajuan_wajib()
    {
        $data = [];

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.pengajuan.update', $this->pengajuanDiantar->id), $data);

        // Controller menggunakan try-catch yang menangkap ValidationException
        // sehingga error akan di-redirect dengan session error
        $response->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_validasi_status_pengajuan_harus_valid()
    {
        $data = [
            'status_pengajuan' => 'status_invalid'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.pengajuan.update', $this->pengajuanDiantar->id), $data);

        // Controller menggunakan try-catch yang menangkap ValidationException
        // sehingga error akan di-redirect dengan session error
        $response->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_update_pengajuan_diantar_berhasil()
    {
        $data = [
            'status_pengajuan' => 'diterima',
            'id_kategori' => $this->kategori->id,
            'parameter' => [$this->parameter->id]
        ];

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.pengajuan.update', $this->pengajuanDiantar->id), $data);

        $response->assertRedirect(route('pegawai.pengajuan.index'))
            ->assertSessionHas('message', 'Pengajuan Telah Diterima!');

        $this->assertDatabaseHas('form_pengajuan', [
            'id' => $this->pengajuanDiantar->id,
            'status_pengajuan' => 'diterima'
        ]);
    }

    public function test_update_pengajuan_ditolak_berhasil()
    {
        $data = ['status_pengajuan' => 'ditolak'];

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.pengajuan.update', $this->pengajuanDiantar->id), $data);

        $response->assertRedirect(route('pegawai.pengajuan.index'))
            ->assertSessionHas('message', 'Pengajuan Telah Ditolak!');

        $this->assertDatabaseHas('form_pengajuan', [
            'id' => $this->pengajuanDiantar->id,
            'status_pengajuan' => 'ditolak'
        ]);
    }
}
