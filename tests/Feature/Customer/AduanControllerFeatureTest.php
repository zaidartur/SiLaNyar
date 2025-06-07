<?php

namespace Tests\Feature\Customer;

use App\Models\Aduan;
use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AduanControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $otherCustomer;
    protected Instansi $instansi;
    protected Instansi $otherInstansi;
    protected HasilUji $hasilUji;
    protected HasilUji $otherHasilUji;
    protected Pengujian $pengujian;
    protected FormPengajuan $pengajuan;

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

        $this->otherCustomer = User::factory()->create();
        $this->otherCustomer->assignRole($customerRole);

        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $this->otherInstansi = Instansi::factory()->create(['id_user' => $this->otherCustomer->id]);

        $jenisCairan = JenisCairan::factory()->create();
        $kategori = Kategori::factory()->create();
        $parameter = ParameterUji::factory()->create();

        $this->pengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'id_kategori' => $kategori->id
        ]);

        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $this->pengajuan->id,
            'id_user' => $this->customer->id
        ]);

        $this->hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'selesai'
        ]);

        $otherPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->otherInstansi->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'id_kategori' => $kategori->id
        ]);

        $otherPengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $otherPengajuan->id,
            'id_user' => $this->otherCustomer->id
        ]);

        $this->otherHasilUji = HasilUji::factory()->create([
            'id_pengujian' => $otherPengujian->id,
            'status' => 'selesai'
        ]);
    }

    public function test_create_memblokir_akses_hasil_uji_milik_customer_lain()
    {
        $this->otherHasilUji->load('pengujian.form_pengajuan.instansi');

        $response = $this->actingAs($this->customer)
            ->get("/customer/hasiluji/aduan/{$this->otherHasilUji->id}");

        $response->assertStatus(403);
    }

    public function test_store_memblokir_pembuatan_aduan_untuk_hasil_uji_milik_customer_lain()
    {
        $this->otherHasilUji->load('pengujian.form_pengajuan.instansi');

        $data = [
            'masalah' => 'Masalah valid',
            'perbaikan' => 'Perbaikan valid'
        ];

        $response = $this->actingAs($this->customer)
            ->post("/customer/hasiluji/aduan/{$this->otherHasilUji->id}", $data);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('aduan', [
            'id_hasil_uji' => $this->otherHasilUji->id,
            'id_user' => $this->customer->id
        ]);
    }

    public function test_store_berhasil_dengan_data_valid_menggunakan_factory()
    {
        $aduan = Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'id_user' => $this->customer->id,
            'masalah' => 'Test masalah',
            'perbaikan' => 'Test perbaikan'
        ]);

        $this->assertDatabaseHas('aduan', [
            'id_hasil_uji' => $this->hasilUji->id,
            'id_user' => $this->customer->id,
            'masalah' => 'Test masalah',
            'perbaikan' => 'Test perbaikan'
        ]);

        $this->assertStringStartsWith('AU-', $aduan->kode_aduan);
    }

    public function test_aduan_dapat_dibuat_dengan_status_kosong()
    {
        $aduan = Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'id_user' => $this->customer->id,
            'status' => null
        ]);

        $this->assertNull($aduan->status);
    }

    public function test_aduan_dapat_memiliki_diverifikasi_oleh()
    {
        $aduan = Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'id_user' => $this->customer->id,
            'diverifikasi_oleh' => 'Admin Test'
        ]);

        $this->assertEquals('Admin Test', $aduan->diverifikasi_oleh);
    }

    public function test_multiple_aduan_dapat_dibuat_untuk_user_berbeda()
    {
        $aduan1 = Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'id_user' => $this->customer->id
        ]);

        $aduan2 = Aduan::factory()->create([
            'id_hasil_uji' => $this->otherHasilUji->id,
            'id_user' => $this->otherCustomer->id
        ]);

        $this->assertNotEquals($aduan1->id_user, $aduan2->id_user);
        $this->assertNotEquals($aduan1->id_hasil_uji, $aduan2->id_hasil_uji);
        $this->assertNotEquals($aduan1->kode_aduan, $aduan2->kode_aduan);
    }
}
