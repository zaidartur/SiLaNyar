<?php

namespace Tests\Feature\Customer;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PengajuanControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Instansi $instansi;
    protected JenisCairan $jenisCairan;
    protected Kategori $kategori;
    protected ParameterUji $parameter;

    public function setUp(): void
    {
        parent::setUp();

        config(['app.asset_url' => null]);

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $this->jenisCairan = JenisCairan::factory()->create([
            'batas_minimum' => 10,
            'batas_maksimum' => 100
        ]);
        $this->kategori = Kategori::factory()->create();
        $this->parameter = ParameterUji::factory()->create();
    }

    public function test_index_menampilkan_daftar_pengajuan_dengan_data_yang_diperlukan()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.pengajuan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/pengajuan/Index')
                ->has('kategori')
                ->has('jenis_cairan')
                ->has('parameter')
                ->has('instansi')
            );
    }

    public function test_store_membuat_pengajuan_dengan_metode_diantar()
    {
        $data = [
            'id_instansi' => $this->instansi->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'volume_sampel' => 50.0,
            'metode_pengambilan' => 'diantar',
            'lokasi' => 'Jl. Lawu No.204',
            'waktu_pengambilan' => now()->addDays(3)->format('Y-m-d'),
            'id_kategori' => $this->kategori->id,
            'parameter' => [$this->parameter->id],
            'keterangan' => 'Test pengajuan'
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('customer.pengajuan.store'), $data);

        $response->assertRedirect(route('customer.dashboard'))
            ->assertSessionHas('message', 'Pengajuan Berhasil Ditambahkan');
    }

    public function test_show_menampilkan_detail_pengajuan()
    {
        $pengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id
        ]);

        $pengajuan->parameter()->attach($this->parameter->id);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.pengajuan.detail', $pengajuan->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/pengajuan/Detail')
                ->has('pengajuan')
            );
    }

    public function test_show_membatasi_akses_hanya_pengajuan_sendiri()
    {
        $otherUser = User::factory()->create();
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        
        $pengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $otherInstansi->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.pengajuan.detail', $pengajuan->id));

        $response->assertStatus(404);
    }
}
