<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PegawaiHasilUjiControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected Pengujian $pengujian;
    protected Pengujian $pengujianTanpaHasilUji;
    protected HasilUji $hasilUji;
    protected ParameterUji $parameter;
    protected Kategori $kategori;

    public function setUp(): void
    {
        parent::setUp();

        config(['app.asset_url' => null]);

        $pegawaiRole = Role::firstOrCreate(
            ['name' => 'pegawai', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002']
        );

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        $permissions = [
            ['name' => 'lihat hasil uji', 'kode_permission' => 'PS-001'],
            ['name' => 'tambah hasil uji', 'kode_permission' => 'PS-002'],
            ['name' => 'edit hasil uji', 'kode_permission' => 'PS-003'],
            ['name' => 'hapus hasil uji', 'kode_permission' => 'PS-004'],
            ['name' => 'detail hasil uji', 'kode_permission' => 'PS-005'],
            ['name' => 'edit status hasil uji', 'kode_permission' => 'PS-006'],
            ['name' => 'riwayat hasil uji', 'kode_permission' => 'PS-007']
        ];

        $permissionNames = [];
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['kode_permission' => $permission['kode_permission']]
            );
            $permissionNames[] = $perm->name;
        }

        $pegawaiRole->syncPermissions($permissionNames);

        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        $this->kategori = Kategori::factory()->create();
        $this->parameter = ParameterUji::factory()->create();

        $this->kategori->parameter()->attach($this->parameter->id, ['baku_mutu' => 10.5]);

        $instansi = \App\Models\Instansi::factory()->create(['id_user' => $this->customer->id]);
        $jenisCairan = \App\Models\JenisCairan::factory()->create();

        $formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'status_pengajuan' => 'diterima'
        ]);

        $formPengajuan2 = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'status_pengajuan' => 'diterima'
        ]);

        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id,
            'id_user' => $this->pegawai->id
        ]);

        $this->pengujianTanpaHasilUji = Pengujian::factory()->create([
            'id_form_pengajuan' => $formPengajuan2->id,
            'id_user' => $this->pegawai->id
        ]);

        $this->hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf'
        ]);

        Storage::fake('local');
    }

    public function test_index_menampilkan_daftar_hasil_uji()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.index'));

        $response->assertStatus(200);
    }


    public function test_show_menampilkan_detail_hasil_uji()
    {
        DB::table('parameter_pengujian')->insert([
            'id_pengujian' => $this->pengujian->id,
            'id_parameter' => $this->parameter->id,
            'nilai' => '8.5',
            'keterangan' => 'Memenuhi baku mutu',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.detail', $this->hasilUji->id));

        $response->assertStatus(200);
    }

    public function test_destroy_menghapus_hasil_uji()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.hasil_uji.destroy', $this->hasilUji->id));

        $response->assertRedirect(route('pegawai.hasil_uji.index'));
        $this->assertDatabaseMissing('hasil_uji', [
            'id' => $this->hasilUji->id
        ]);
    }

    public function test_middleware_permission_memblokir_akses_tanpa_permission()
    {
        $userTanpaPermission = User::factory()->create();
        $roleCustomer = Role::where('name', 'customer')->first();
        if ($roleCustomer) {
            $userTanpaPermission->assignRole($roleCustomer);
        }

        $response = $this->actingAs($userTanpaPermission)
            ->get(route('pegawai.hasil_uji.index'));

        $response->assertStatus(403);
    }
}