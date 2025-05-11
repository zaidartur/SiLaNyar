<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pegawai;
use App\Models\Customer;
use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\JenisCairan;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Route;

class PengajuanForAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $pengajuan;

    public function setUp(): void
    {
        parent::setUp();
        
        // Buat Permission terlebih dahulu
        $permissions = [
            'lihat-pengajuan',
            'detail-pengajuan',
            'edit-pengajuan'
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create([
                'name' => $permission,
                'guard_name' => 'pegawai'
            ]);
        }
        
        $this->admin = Pegawai::factory()->create([
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now(),
            'jabatan' => 'Admin Lab'
        ]);

        // Berikan permission ke admin
        $this->admin->givePermissionTo($permissions);

        $this->pengajuan = FormPengajuan::factory()->create([
            'status_pengajuan' => 'proses_validasi'
        ]);
    }

    public function test_admin_bisa_melihat_daftar_pengajuan()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($this->admin, 'pegawai')
            ->from('/pegawai/dashboard')
            ->get('/pegawai/pengajuan');

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengajuan/index')
                ->has('pengajuan')
            );
    }

    public function test_admin_bisa_melihat_detail_pengajuan()
    {
        $response = $this->actingAs($this->admin, 'pegawai')
            ->get('/pegawai/pengajuan/' . $this->pengajuan->id);

        $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengajuan/detail')
                ->has('pengajuan')
            );
    }

    public function test_admin_bisa_menerima_pengajuan()
    {
        $kategori = Kategori::factory()->create(['harga' => 150000]);
        
        $pengajuan = FormPengajuan::factory()->create([
            'id_kategori' => $kategori->id,
            'status_pengajuan' => 'proses_validasi'
        ]);

        $response = $this->actingAs($this->admin, 'pegawai')
            ->put('/pegawai/pengajuan/' . $pengajuan->id . '/edit', [
                'status_pengajuan' => 'diterima'
            ]);

        $response->assertRedirect('/pegawai/pengajuan');
        
        $this->assertDatabaseHas('form_pengajuan', [
            'id' => $pengajuan->id,
            'status_pengajuan' => 'diterima'
        ]);

        $this->assertDatabaseHas('pembayaran', [
            'id_form_pengajuan' => $pengajuan->id,
            'total_biaya' => 150000,
            'status_pembayaran' => 'belum_dibayar'
        ]);
    }

    public function test_admin_bisa_menolak_pengajuan()
    {
        $response = $this->actingAs($this->admin, 'pegawai')
            ->put(route('pegawai.pengajuan.update', $this->pengajuan->id), [
                'status_pengajuan' => 'ditolak'
            ]);

        $response->assertRedirect(route('pegawai.pengajuan.index'));
        
        $this->assertDatabaseHas('form_pengajuan', [
            'id' => $this->pengajuan->id,
            'status_pengajuan' => 'ditolak'
        ]);
    }

    public function test_admin_harus_memilih_status_valid()
    {
        $response = $this->actingAs($this->admin, 'pegawai')
            ->put(route('pegawai.pengajuan.update', $this->pengajuan->id), [
                'status_pengajuan' => 'status_tidak_valid'
            ]);

        $response->assertSessionHasErrors('status_pengajuan');
    }


    public function test_pengajuan_diterima_membuat_pembayaran()
    {
        $kategori = Kategori::factory()->create(['harga' => 500000]);
        $pengajuan = FormPengajuan::factory()->create([
            'id_kategori' => $kategori->id,
            'status_pengajuan' => 'proses_validasi'
        ]);

        $this->actingAs($this->admin, 'pegawai')
            ->put(route('pegawai.pengajuan.update', $pengajuan->id), [
                'status_pengajuan' => 'diterima'
            ]);

        $this->assertDatabaseHas('pembayaran', [
            'id_form_pengajuan' => $pengajuan->id,
            'total_biaya' => 500000,
            'status_pembayaran' => 'belum_dibayar'
        ]);
    }
}
