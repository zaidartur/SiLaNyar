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
use Spatie\Permission\Models\Permission;
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

        // Configure Vite for testing
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
                ->where('instansi.0.id', $this->instansi->id)
                ->where('kategori.0.id', $this->kategori->id)
                ->where('jenis_cairan.0.id', $this->jenisCairan->id)
                ->where('parameter.0.id', $this->parameter->id)
                ->etc()
            );
    }

    public function test_store_membuat_pengajuan_dengan_metode_diantar()
    {
        $data = [
            'id_instansi' => $this->instansi->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'volume_sampel' => 50.0, // Within limits
            'metode_pengambilan' => 'diantar',
            'lokasi' => 'Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716 (DLH Kabupaten Karanganyar)', // Default DLH location
            'waktu_pengambilan' => now()->addDays(3)->format('Y-m-d'),
            'id_kategori' => $this->kategori->id,
            'parameter' => [$this->parameter->id],
            'keterangan' => 'Test pengajuan'
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('customer.pengajuan.store'), $data);

        $response->assertRedirect(route('customer.dashboard'))
            ->assertSessionHas('message', 'Pengajuan Berhasil Ditambahkan');

        $this->assertDatabaseHas('form_pengajuan', [
            'id_instansi' => $this->instansi->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'volume_sampel' => 50.0,
            'metode_pengambilan' => 'diantar',
            'status_pengajuan' => 'proses_validasi'
        ]);

        $this->assertDatabaseHas('jadwal', [
            'id_user' => $this->customer->id,
            'keterangan' => 'Test pengajuan',
            'status' => 'diproses'
        ]);
    }

    public function test_store_membuat_pengajuan_dengan_metode_diambil()
    {
        $data = [
            'id_instansi' => $this->instansi->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'volume_sampel' => 25.0, // Within limits
            'metode_pengambilan' => 'diambil',
            'lokasi' => 'Jl. Test No. 123',
            'id_kategori' => $this->kategori->id,
            'parameter' => [$this->parameter->id]
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('customer.pengajuan.store'), $data);

        $response->assertRedirect(route('customer.dashboard'));

        $this->assertDatabaseHas('form_pengajuan', [
            'metode_pengambilan' => 'diambil',
            'lokasi' => 'Jl. Test No. 123'
        ]);

        $this->assertDatabaseMissing('jadwal', [
            'id_form_pengajuan' => FormPengajuan::latest()->first()->id
        ]);
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
                ->has('pengajuan', fn (Assert $pengajuanData) => $pengajuanData
                    ->where('id', $pengajuan->id)
                    ->has('kategori')
                    ->has('parameter', 1)
                    ->has('jenis_cairan')
                    ->has('instansi.user')
                    ->where('id_instansi', $this->instansi->id)
                    ->etc()
                )
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

    public function test_edit_menampilkan_form_untuk_pengajuan_yang_valid()
    {
        $pengajuan = FormPengajuan::factory()->prosesValidasi()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.pengajuan.edit', $pengajuan));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/pengajuan/Edit')
                ->has('pengajuan', fn (Assert $pengajuan) => $pengajuan
                    ->where('status_pengajuan', 'proses_validasi')
                    ->has('kategori')
                    ->has('parameter')
                    ->has('instansi.user')
                    ->has('jenis_cairan')
                    ->etc()
                )
                ->has('kategori')
                ->has('jenis_cairan')
                ->has('parameter')
            );
    }

    public function test_edit_memblokir_pengajuan_selain_proses_validasi()
    {
        $pengajuan = FormPengajuan::factory()->diterima()->create([
            'id_instansi' => $this->instansi->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.pengajuan.edit', $pengajuan));

        $response->assertStatus(403);
    }

    public function test_destroy_menghapus_pengajuan_yang_valid()
    {
        $pengajuan = FormPengajuan::factory()->prosesValidasi()->create([
            'id_instansi' => $this->instansi->id
        ]);

        $response = $this->actingAs($this->customer)
            ->delete(route('customer.pengajuan.delete', $pengajuan->id));

        $response->assertRedirect(route('customer.dashboard'));

        $this->assertDatabaseMissing('form_pengajuan', [
            'id' => $pengajuan->id
        ]);
    }

    public function test_destroy_memblokir_pengajuan_yang_diterima()
    {
        $pengajuan = FormPengajuan::factory()->diterima()->create([
            'id_instansi' => $this->instansi->id
        ]);

        $response = $this->actingAs($this->customer)
            ->delete(route('customer.pengajuan.delete', $pengajuan->id));

        $response->assertStatus(404); // Because destroy method filters by status

        $this->assertDatabaseHas('form_pengajuan', [
            'id' => $pengajuan->id
        ]);
    }

    public function test_store_memvalidasi_volume_sampel_terhadap_batas_jenis_cairan()
    {
        $data = [
            'id_instansi' => $this->instansi->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'volume_sampel' => 5, // Below minimum
            'metode_pengambilan' => 'diantar',
            'waktu_pengambilan' => now()->addDays(3)->format('Y-m-d'),
            'lokasi' => 'Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716 (DLH Kabupaten Karanganyar)',
            'id_kategori' => $this->kategori->id,
            'parameter' => [$this->parameter->id]
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('customer.pengajuan.store'), $data);

        $response->assertSessionHasErrors(['volume_sampel']);
    }
}
