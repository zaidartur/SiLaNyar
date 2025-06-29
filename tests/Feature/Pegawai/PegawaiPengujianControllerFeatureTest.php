<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\Pengujian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Carbon\Carbon;

class PegawaiPengujianControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $teknisi;
    protected User $customer;
    protected FormPengajuan $formPengajuanDiterima;
    protected Kategori $kategori;
    protected Pengujian $pengujian;

    public function setUp(): void
    {
        parent::setUp();

        config(['app.asset_url' => null]);

        $pegawaiRole = Role::firstOrCreate(
            ['name' => 'pegawai', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002']
        );
        $teknisiRole = Role::firstOrCreate(
            ['name' => 'teknisi', 'guard_name' => 'web'],
            ['kode_role' => 'RL-003']
        );
        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        $permissions = [
            ['name' => 'lihat pengujian', 'kode' => 'PS-011'],
            ['name' => 'tambah pengujian', 'kode' => 'PS-012'],
            ['name' => 'edit pengujian', 'kode' => 'PS-013'],
            ['name' => 'detail pengujian', 'kode' => 'PS-014'],
            ['name' => 'hapus pengujian', 'kode' => 'PS-015'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['kode_permission' => $permission['kode']]
            );
        }

        $pegawaiRole->givePermissionTo(array_column($permissions, 'name'));

        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);

        $this->teknisi = User::factory()->create();
        $this->teknisi->assignRole($teknisiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        $this->kategori = Kategori::factory()->create();

        $instansi = \App\Models\Instansi::factory()->create(['id_user' => $this->customer->id]);
        $jenisCairan = \App\Models\JenisCairan::factory()->create();

        $this->formPengajuanDiterima = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'status_pengajuan' => 'diterima'
        ]);

        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'status' => 'diproses'
        ]);
    }

    public function test_index_menampilkan_daftar_pengujian()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengujian.index'));

        $response->assertStatus(200);
    }

    public function test_store_membuat_pengujian_berhasil()
    {
        $tanggalMulai = Carbon::now()->addDays(1)->format('Y-m-d');
        $tanggalSelesai = Carbon::now()->addDays(3)->format('Y-m-d');

        $data = [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $response->assertRedirect(route('pegawai.pengujian.index'));
        $this->assertDatabaseHas('pengujian', [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'status' => 'diproses'
        ]);
    }

    public function test_show_menampilkan_detail_pengujian()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengujian.detail', $this->pengujian->id));

        $response->assertStatus(200);
    }

    public function test_destroy_pengujian_berhasil()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.pengujian.destroy', $this->pengujian->id));

        $response->assertRedirect(route('pegawai.pengujian.index'));
        $this->assertDatabaseMissing('pengujian', [
            'id' => $this->pengujian->id
        ]);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.pengujian.index'));

        $response->assertStatus(403);
    }
}