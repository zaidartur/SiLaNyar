<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiPembayaranControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected Pembayaran $pembayaranBelumDibayar;
    protected Pembayaran $pembayaranDiproses;
    protected Pembayaran $pembayaranSelesai;
    protected FormPengajuan $formPengajuan;
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
            ['name' => 'kelola pembayaran', 'kode' => 'PS-007'],
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

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        $instansi = \App\Models\Instansi::factory()->create(['id_user' => $this->customer->id]);
        $this->kategori = Kategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        $this->kategori->parameter()->attach($parameter->id, ['baku_mutu' => 'Test Baku Mutu']);

        $this->formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $this->kategori->id,
        ]);

        $this->pembayaranBelumDibayar = Pembayaran::factory()->belumDibayar()->create([
            'id_form_pengajuan' => $this->formPengajuan->id,
            'total_biaya' => 500000,
        ]);

        $formPengajuan2 = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $this->kategori->id,
        ]);
        $this->pembayaranDiproses = Pembayaran::factory()->diproses()->create([
            'id_form_pengajuan' => $formPengajuan2->id,
            'total_biaya' => 750000,
        ]);

        $formPengajuan3 = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $this->kategori->id,
        ]);
        $this->pembayaranSelesai = Pembayaran::factory()->selesai()->create([
            'id_form_pengajuan' => $formPengajuan3->id,
            'total_biaya' => 300000,
            'metode_pembayaran' => 'transfer',
        ]);
    }

    public function test_index_menampilkan_daftar_pembayaran()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.index'));

        $response->assertStatus(200);
    }

    public function test_show_menampilkan_detail_pembayaran()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.detail', $this->pembayaranSelesai->id));

        $response->assertStatus(200);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.pembayaran.index'));

        $response->assertStatus(403);
    }
}