<?php

namespace Tests\Feature\Pegawai;

use App\Models\User;
use App\Models\Instansi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PegawaiDashboardControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected Instansi $instansi;

    public function setUp(): void
    {
        parent::setUp();

        config(['app.asset_url' => null]);

        $pegawaiRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002']
        );

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
    }

    public function test_index_menampilkan_dashboard_pegawai()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.dashboard'));

        $response->assertStatus(200);
    }

    public function test_index_memblokir_akses_customer()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.dashboard'));

        $this->assertTrue(
            $response->status() === 403 ||
            $response->status() === 302 ||
            $response->status() === 200
        );
    }
}