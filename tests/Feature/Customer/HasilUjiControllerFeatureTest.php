<?php

namespace Tests\Feature\Customer;

use App\Models\Aduan;
use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HasilUjiControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Instansi $instansi;
    protected FormPengajuan $pengajuan;
    protected Pengujian $pengujian;
    protected HasilUji $hasilUji;
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
        
        $this->kategori = Kategori::factory()->create();
        $this->parameter = ParameterUji::factory()->create();
        
        $this->pengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id
        ]);

        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $this->pengajuan->id
        ]);

        $this->hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'selesai',
            'file_pdf' => 'hasil_uji/test-file.pdf'
        ]);

        Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'perbaikan' => 'Test perbaikan'
        ]);
    }

    public function test_index_menampilkan_daftar_hasil_uji_dengan_aduan()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/hasil_uji/Index')
                ->has('hasil_uji', 0)
            );
    }

    public function test_index_membatasi_hasil_uji_milik_instansi_customer()
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole('customer');
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        $otherPengajuan = FormPengajuan::factory()->create(['id_instansi' => $otherInstansi->id]);
        $otherPengujian = Pengujian::factory()->create(['id_form_pengajuan' => $otherPengajuan->id]);
        $otherHasilUji = HasilUji::factory()->create([
            'id_pengujian' => $otherPengujian->id,
            'status' => 'selesai',
            'file_pdf' => 'hasil_uji/other-file.pdf'
        ]);
        Aduan::factory()->create([
            'id_hasil_uji' => $otherHasilUji->id,
            'perbaikan' => 'Short text'
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/hasil_uji/Index')
                ->has('hasil_uji', 0)
            );
    }

    public function test_show_memblokir_akses_hasil_uji_milik_customer_lain()
    {
        $otherUser = User::factory()->create();
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        $otherPengajuan = FormPengajuan::factory()->create(['id_instansi' => $otherInstansi->id]);
        $otherPengujian = Pengujian::factory()->create(['id_form_pengajuan' => $otherPengajuan->id]);
        $otherHasilUji = HasilUji::factory()->create([
            'id_pengujian' => $otherPengujian->id,
            'status' => 'selesai',
            'file_pdf' => 'hasil_uji/other-file.pdf'
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.detail', $otherHasilUji->id));

        $response->assertStatus(403);
    }
}