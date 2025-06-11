<?php

namespace Tests\Feature\Pegawai;

use App\Models\Aduan;
use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\Instansi;
use App\Models\Pengujian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiVerifikasiAduanControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected Aduan $aduan;
    protected HasilUji $hasilUji;
    protected Pengujian $pengujian;
    protected FormPengajuan $formPengajuan;
    protected Instansi $instansi;

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
        $permission = Permission::firstOrCreate(
            ['name' => 'kelola aduan', 'guard_name' => 'web'],
            ['kode_permission' => 'PS-012']
        );

        // Berikan permission kepada role pegawai
        $pegawaiRole->givePermissionTo($permission);

        // Buat user
        $this->pegawai = User::factory()->create(['nama' => 'Pegawai Test']);
        $this->pegawai->assignRole($pegawaiRole);

        $this->customer = User::factory()->create(['nama' => 'Customer Test']);
        $this->customer->assignRole($customerRole);

        // Buat data pendukung
        $this->instansi = Instansi::factory()->create();
        $this->instansi->user()->associate($this->customer);
        $this->instansi->save();

        $this->formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id
        ]);

        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $this->formPengajuan->id,
            'status' => 'selesai'
        ]);

        // Set status hasil_uji ke 'proses_peresmian' secara eksplisit
        $this->hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'proses_peresmian'
        ]);

        $this->aduan = Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'id_user' => $this->customer->id,
            'status' => null,
            'diverifikasi_oleh' => null
        ]);
    }

    public function test_index_menampilkan_daftar_aduan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.aduan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/aduan/Index')
                ->has('aduan', 1)
                ->has('aduan.0', fn (Assert $aduanItem) => $aduanItem
                    ->where('id', $this->aduan->id)
                    ->where('kode_aduan', $this->aduan->kode_aduan)
                    ->where('masalah', $this->aduan->masalah)
                    ->where('perbaikan', $this->aduan->perbaikan)
                    ->where('status', $this->aduan->status)
                    ->has('user')
                    ->has('hasil_uji')
                    ->etc()
                )
            );
    }

    public function test_show_menampilkan_detail_aduan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.aduan.detail', $this->aduan->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/aduan/Show')
                ->has('aduan', fn (Assert $aduan) => $aduan
                    ->where('id', $this->aduan->id)
                    ->where('kode_aduan', $this->aduan->kode_aduan)
                    ->where('masalah', $this->aduan->masalah)
                    ->where('perbaikan', $this->aduan->perbaikan)
                    ->where('status', $this->aduan->status)
                    ->where('diverifikasi_oleh', $this->aduan->diverifikasi_oleh)
                    ->has('user')
                    ->has('hasil_uji')
                    ->etc()
                )
            );
    }

    public function test_verifikasi_aduan_diterima_administrasi_berhasil()
    {
        $data = [
            'status' => 'diterima_administrasi'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/aduan/verifikasi/{$this->aduan->id}", $data);

        $response->assertRedirect(route('pegawai.aduan.index'))
            ->assertSessionHas('message', 'Aduan Berhasil Diverifikasi');

        $this->assertDatabaseHas('aduan', [
            'id' => $this->aduan->id,
            'status' => 'diterima_administrasi',
            'diverifikasi_oleh' => $this->pegawai->nama
        ]);

        $this->assertDatabaseHas('hasil_uji', [
            'id' => $this->hasilUji->id,
            'status' => 'revisi'
        ]);
    }

    public function test_verifikasi_aduan_diterima_pengujian_berhasil()
    {
        $data = [
            'status' => 'diterima_pengujian'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/aduan/verifikasi/{$this->aduan->id}", $data);

        $response->assertRedirect(route('pegawai.aduan.index'))
            ->assertSessionHas('message', 'Aduan Berhasil Diverifikasi');

        $this->assertDatabaseHas('aduan', [
            'id' => $this->aduan->id,
            'status' => 'diterima_pengujian',
            'diverifikasi_oleh' => $this->pegawai->nama
        ]);

        $this->assertDatabaseHas('pengujian', [
            'id' => $this->pengujian->id,
            'status' => 'diproses'
        ]);
    }

    public function test_verifikasi_aduan_ditolak_berhasil()
    {
        // Pastikan status awal hasil_uji
        $statusAwal = $this->hasilUji->status;
        
        $data = [
            'status' => 'ditolak'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/aduan/verifikasi/{$this->aduan->id}", $data);

        $response->assertRedirect(route('pegawai.aduan.index'))
            ->assertSessionHas('message', 'Aduan Berhasil Diverifikasi');

        $this->assertDatabaseHas('aduan', [
            'id' => $this->aduan->id,
            'status' => 'ditolak',
            'diverifikasi_oleh' => $this->pegawai->nama
        ]);

        $this->assertDatabaseHas('hasil_uji', [
            'id' => $this->hasilUji->id,
            'status' => $statusAwal
        ]);
    }

    public function test_verifikasi_validasi_status_wajib()
    {
        $data = [];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/aduan/verifikasi/{$this->aduan->id}", $data);

        $response->assertSessionHasErrors(['status']);
    }

    public function test_verifikasi_validasi_status_harus_valid()
    {
        $data = [
            'status' => 'status_invalid'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/aduan/verifikasi/{$this->aduan->id}", $data);

        $response->assertSessionHasErrors(['status']);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.aduan.index'));

        $response->assertStatus(403);
    }

    public function test_kode_aduan_otomatis_generated()
    {
        $aduanBaru = Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'id_user' => $this->customer->id,
            'masalah' => 'Test masalah',
            'perbaikan' => 'Test perbaikan',
        ]);

        $this->assertNotNull($aduanBaru->kode_aduan);
        $this->assertMatchesRegularExpression('/^AU-\d{3}$/', $aduanBaru->kode_aduan);
    }

    public function test_show_aduan_tidak_ditemukan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.aduan.detail', 99999));

        $response->assertStatus(404);
    }

    public function test_verifikasi_aduan_tidak_ditemukan()
    {
        $data = [
            'status' => 'diterima_administrasi'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put('/pegawai/aduan/verifikasi/99999', $data);

        $response->assertStatus(404);
    }
}
