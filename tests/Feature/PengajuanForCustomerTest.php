<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\FormPengajuan;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class PengajuanForCustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $kategori;
    protected $jenisCairan;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->customer = Customer::factory()->create([
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now()
        ]);

        $this->kategori = Kategori::factory()->create();
        $this->jenisCairan = JenisCairan::factory()->create([
            'batas_minimum' => 100,
            'batas_maksimum' => 1000
        ]);
    }

    public function test_customer_bisa_melihat_halaman_daftar_pengajuan()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.pengajuan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/pengajuan/index')
                ->has('pengajuan')
            );
    }

    public function test_customer_bisa_melihat_form_tambah_pengajuan()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.pengajuan.daftar'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/pengajuan/tambah')
                ->has('kategori')
                ->has('jenis_cairan')
                ->has('parameter')
            );
    }

    public function test_customer_bisa_membuat_pengajuan_baru()
    {
        $parameter = ParameterUji::factory()->create();

        $pengajuanData = [
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'volume_sampel' => 500.0,
            'metode_pengambilan' => 'diantar',
            'parameter' => [$parameter->id],
            'lokasi' => null
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->from('/customer/pengajuan/daftar')
            ->post(route('customer.pengajuan.store'), $pengajuanData);

        $response->assertRedirect(route('customer.pengajuan.index'));
        
        $this->assertDatabaseHas('form_pengajuan', [
            'id_customer' => $this->customer->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'volume_sampel' => 500,
            'metode_pengambilan' => 'diantar'
        ]);
    }

    public function test_validasi_volume_sampel_sesuai_batas()
    {
        $parameter = ParameterUji::factory()->create();
        
        $response = $this->actingAs($this->customer, 'customer')
            ->from('/customer/pengajuan/daftar')
            ->post(route('customer.pengajuan.store'), [
                'id_kategori' => $this->kategori->id,
                'id_jenis_cairan' => $this->jenisCairan->id,
                'volume_sampel' => 50.0, // Di bawah batas minimum (100)
                'metode_pengambilan' => 'diantar',
                'parameter' => [$parameter->id],
                'lokasi' => null
            ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['volume_sampel' => 'Volume Sampel Harus Diantara 100 atau 1000 Untuk Jenis Cairan']);
    }

    public function test_lokasi_wajib_diisi_untuk_metode_diambil()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.pengajuan.store'), [
                'id_kategori' => $this->kategori->id,
                'id_jenis_cairan' => $this->jenisCairan->id,
                'volume_sampel' => 500.0,
                'metode_pengambilan' => 'diambil',
                'lokasi' => '', // Lokasi kosong
                'parameter' => [1]
            ]);

        $response->assertSessionHasErrors('lokasi');
    }

    public function test_customer_bisa_melihat_detail_pengajuan()
    {
        // Buat parameter untuk pengajuan
        $parameter = ParameterUji::factory()->create();
        
        // Buat pengajuan dengan relasi parameter
        $pengajuan = FormPengajuan::factory()->create([
            'id_customer' => $this->customer->id
        ]);
        
        // Attach parameter ke pengajuan
        $pengajuan->parameter()->attach($parameter->id);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.pengajuan.show', $pengajuan->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/pengajuan/detail')
                ->has('pengajuan')
            );
    }

    public function test_customer_tidak_bisa_melihat_pengajuan_customer_lain()
    {
        $customerLain = Customer::factory()->create();
        $pengajuan = FormPengajuan::factory()->create([
            'id_customer' => $customerLain->id
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.pengajuan.show', $pengajuan->id));

        $response->assertStatus(404);
    }

    public function test_parameter_wajib_dipilih()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.pengajuan.store'), [
                'id_kategori' => $this->kategori->id,
                'id_jenis_cairan' => $this->jenisCairan->id,
                'volume_sampel' => 500.0,
                'metode_pengambilan' => 'diantar',
                'parameter' => [] // Parameter kosong
            ]);

        $response->assertSessionHasErrors('parameter');
    }
}
