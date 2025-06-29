<?php

namespace Tests\Feature\Customer;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JadwalControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Instansi $instansi;
    protected FormPengajuan $pengajuan;
    protected Jadwal $jadwal;

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
        
        $this->pengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'metode_pengambilan' => 'diantar'
        ]);

        $this->jadwal = Jadwal::factory()->create([
            'id_form_pengajuan' => $this->pengajuan->id,
            'id_user' => $this->customer->id,
            'status' => 'diproses'
        ]);
    }

    public function test_index_menampilkan_daftar_jadwal_customer()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Pengantaran')
                ->has('jadwal', 1)
            );
    }

    public function test_show_menampilkan_detail_jadwal_dengan_relasi()
    {
        $response = $this->actingAs($this->customer)
            ->get('/customer/jadwal/' . $this->jadwal->id);

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Detail')
                ->has('jadwal')
            );
    }

    public function test_show_membatasi_akses_jadwal_milik_customer_lain()
    {
        $otherUser = \App\Models\User::factory()->create();
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        $otherPengajuan = FormPengajuan::factory()->create(['id_instansi' => $otherInstansi->id]);
        $otherJadwal = Jadwal::factory()->create([
            'id_form_pengajuan' => $otherPengajuan->id,
            'id_user' => $otherUser->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get('/customer/jadwal/' . $otherJadwal->id);

        $response->assertStatus(404);
    }
}