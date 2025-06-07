<?php

namespace Tests\Feature\Customer;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\Jadwal;
use App\Models\JenisCairan;
use App\Models\Kategori;
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
                ->has('jadwal', 1, fn (Assert $jadwal) => $jadwal
                    ->where('id', $this->jadwal->id)
                    ->where('status', 'diproses')
                    ->where('id_user', $this->customer->id)
                    ->has('form_pengajuan')
                    ->etc()
                )
                ->where('jadwalAntarTerbaru', $this->jadwal->id)
                ->has('jadwalAmbilTerbaru')
                ->has('filter')
                ->where('filter.status', null)
                ->etc()
            );
    }

    public function test_index_dapat_difilter_berdasarkan_status()
    {
        Jadwal::factory()->create([
            'id_form_pengajuan' => FormPengajuan::factory()->create([
                'id_instansi' => $this->instansi->id
            ])->id,
            'id_user' => $this->customer->id,
            'status' => 'selesai'
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.index', ['status' => 'selesai']));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Pengantaran')
                ->where('filter.status', 'selesai')
                ->etc()
            );
    }

    public function test_pengantaran_menampilkan_jadwal_metode_diantar()
    {
        // Create another jadwal with 'diambil' method that should not appear
        $pengajuanAmbil = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'metode_pengambilan' => 'diambil'
        ]);

        Jadwal::factory()->create([
            'id_form_pengajuan' => $pengajuanAmbil->id,
            'id_user' => $this->customer->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.pengantaran'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Pengantaran')
                ->has('jadwalAntarTerbaru', 1, fn (Assert $jadwal) => $jadwal
                    ->has('form_pengajuan', fn (Assert $pengajuan) => $pengajuan
                        ->where('metode_pengambilan', 'diantar')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_penjemputan_menampilkan_jadwal_metode_diambil()
    {
        $pengajuanAmbil = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'metode_pengambilan' => 'diambil'
        ]);

        $jadwalAmbil = Jadwal::factory()->create([
            'id_form_pengajuan' => $pengajuanAmbil->id,
            'id_user' => $this->customer->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.penjemputan'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Penjemputan')
                ->has('jadwalAmbilTerbaru', 1, fn (Assert $jadwal) => $jadwal
                    ->where('id', $jadwalAmbil->id)
                    ->has('form_pengajuan', fn (Assert $pengajuan) => $pengajuan
                        ->where('metode_pengambilan', 'diambil')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    // public function test_show_menampilkan_detail_jadwal_dengan_relasi()
    // {
    //     $kategori = Kategori::factory()->create();
    //     $this->pengajuan->update(['id_kategori' => $kategori->id]);

    //     $response = $this->actingAs($this->customer)
    //         ->get('/customer/jadwal/' . $this->jadwal->id);

    //     $response->assertStatus(200)
    //         ->assertInertia(fn (Assert $page) => $page
    //             ->component('customer/jadwal/Detail')
    //             ->has('jadwal', fn (Assert $jadwal) => $jadwal
    //                 ->where('id', $this->jadwal->id)
    //                 ->where('status', 'diproses')
    //                 ->has('form_pengajuan', fn (Assert $pengajuan) => $pengajuan
    //                     ->where('id', $this->pengajuan->id)
    //                     ->has('kategori', fn (Assert $kategoriData) => $kategoriData
    //                         ->where('id', $kategori->id)
    //                         ->etc()
    //                     )
    //                     ->etc()
    //                 )
    //                 ->etc()
    //             )
    //         );
    // }

    public function test_show_membatasi_akses_jadwal_milik_customer_lain()
    {
        $otherUser = User::factory()->create();
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

    public function test_index_hanya_menampilkan_jadwal_dari_instansi_customer()
    {
        // Create other customer with their own jadwal
        $otherUser = User::factory()->create();
        $otherUser->assignRole('customer');
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        $otherPengajuan = FormPengajuan::factory()->create(['id_instansi' => $otherInstansi->id]);
        Jadwal::factory()->create([
            'id_form_pengajuan' => $otherPengajuan->id,
            'id_user' => $otherUser->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Pengantaran')
                ->has('jadwal', 1) // Only one jadwal should be visible
                ->has('jadwal', 1, fn (Assert $jadwal) => $jadwal
                    ->where('id_user', $this->customer->id)
                    ->etc()
                )
            );
    }

    public function test_pengantaran_mengurutkan_jadwal_berdasarkan_waktu_terbaru()
    {
        // Create older jadwal
        $olderPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'metode_pengambilan' => 'diantar'
        ]);

        $olderJadwal = Jadwal::factory()->create([
            'id_form_pengajuan' => $olderPengajuan->id,
            'id_user' => $this->customer->id,
            'created_at' => now()->subDays(2)
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.pengantaran'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Pengantaran')
                ->has('jadwalAntarTerbaru', 2)
                ->etc()
            );
    }

    public function test_penjemputan_tidak_menampilkan_jadwal_metode_diantar()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.penjemputan'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Penjemputan')
                ->has('jadwalAmbilTerbaru', 0) // No 'diambil' jadwal exists
            );
    }

    public function test_index_menampilkan_jadwal_antar_dan_ambil_terbaru_terpisah()
    {
        // Create 'diambil' jadwal
        $pengajuanAmbil = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'metode_pengambilan' => 'diambil'
        ]);

        $jadwalAmbil = Jadwal::factory()->create([
            'id_form_pengajuan' => $pengajuanAmbil->id,
            'id_user' => $this->customer->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.jadwal.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/jadwal/Pengantaran')
                ->where('jadwalAntarTerbaru', $this->jadwal->id) // diantar jadwal
                ->where('jadwalAmbilTerbaru', $jadwalAmbil->id) // diambil jadwal
                ->etc()
            );
    }
}
