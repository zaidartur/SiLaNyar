<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Carbon\Carbon;

class PegawaiJadwalControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $teknisi;
    protected User $customer;
    protected FormPengajuan $pengajuanDiambil;
    protected Jadwal $jadwal;

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
            ['name' => 'lihat pengambilan', 'kode' => 'PS-001'],
            ['name' => 'tambah pengambilan', 'kode' => 'PS-002'], 
            ['name' => 'edit pengambilan', 'kode' => 'PS-003'],
            ['name' => 'hapus pengambilan', 'kode' => 'PS-004'],
            ['name' => 'detail pengambilan', 'kode' => 'PS-005']
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

        $instansi = \App\Models\Instansi::factory()->create(['id_user' => $this->customer->id]);
        $kategori = \App\Models\Kategori::factory()->create();
        $jenisCairan = \App\Models\JenisCairan::factory()->create();

        $this->pengajuanDiambil = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $kategori->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima'
        ]);

        $this->jadwal = Jadwal::factory()->create([
            'id_form_pengajuan' => $this->pengajuanDiambil->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => 'diproses'
        ]);
    }

    public function test_index_menampilkan_daftar_jadwal_dengan_filter()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.index'));

        $response->assertStatus(200);
    }

    public function test_store_membuat_jadwal_berhasil()
    {
        $pengajuanBaru = FormPengajuan::factory()->create([
            'id_instansi' => $this->pengajuanDiambil->id_instansi,
            'id_kategori' => $this->pengajuanDiambil->id_kategori,
            'id_jenis_cairan' => $this->pengajuanDiambil->id_jenis_cairan,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima'
        ]);

        $data = [
            'id_form_pengajuan' => $pengajuanBaru->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'keterangan' => 'Jadwal pengambilan sampel'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengambilan/store', $data);

        $response->assertRedirect(route('pegawai.pengambilan.index'));
        $this->assertDatabaseHas('jadwal', [
            'id_form_pengajuan' => $pengajuanBaru->id,
            'id_user' => $this->teknisi->id,
            'status' => 'diproses'
        ]);
    }

    public function test_show_menampilkan_detail_jadwal()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.detail', $this->jadwal->id));

        $response->assertStatus(200);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $userTanpaPermission = User::factory()->create();
        $roleCustomer = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );
        $userTanpaPermission->assignRole($roleCustomer);

        $response = $this->actingAs($userTanpaPermission)
            ->get(route('pegawai.pengambilan.index'));

        $response->assertStatus(403);
    }
}
