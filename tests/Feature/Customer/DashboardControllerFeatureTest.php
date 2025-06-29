<?php

namespace Tests\Feature\Customer;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Instansi $instansi;
    protected JenisCairan $jenisCairan;
    protected Kategori $kategori;

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
        $this->jenisCairan = JenisCairan::factory()->create();
        $this->kategori = Kategori::factory()->create();
    }

    public function test_index_menampilkan_dashboard_dengan_statistik_dan_data_pengajuan()
    {
        // Create test data
        $pengajuanProses = FormPengajuan::factory()->prosesValidasi()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id
        ]);

        $pengajuanDiterima = FormPengajuan::factory()->diterima()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id
        ]);

        $pengajuanDitolak = FormPengajuan::factory()->ditolak()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.dashboard'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/dashboard/Index')
                ->has('statistik')
                ->has('pengajuan')
                ->has('auth.user', fn (Assert $user) => $user
                    ->where('id', $this->customer->id)
                    ->etc()
                )
            );
    }

    public function test_index_tidak_menampilkan_pengajuan_instansi_lain()
    {
        $otherUser = User::factory()->create();
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        
        FormPengajuan::factory()->create([
            'id_instansi' => $otherInstansi->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.dashboard'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/dashboard/Index')
                ->has('pengajuan', 0)
            );
    }

    public function test_index_menampilkan_error_jika_tidak_ada_instansi()
    {
        // Delete the instansi
        $this->instansi->delete();

        $response = $this->actingAs($this->customer)
            ->get(route('customer.dashboard'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/dashboard/Index')
                ->where('error', 'Tidak ada instansi yang tersedia')
            );
    }
}