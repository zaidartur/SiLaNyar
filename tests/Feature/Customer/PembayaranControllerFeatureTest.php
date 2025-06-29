<?php

namespace Tests\Feature\Customer;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PembayaranControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Instansi $instansi;
    protected FormPengajuan $pengajuan;
    protected Kategori $kategori;
    protected ParameterUji $parameter;
    protected Pembayaran $pembayaran;

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
        
        $this->kategori = Kategori::factory()->create(['harga' => 500000]);
        $this->parameter = ParameterUji::factory()->create(['harga' => 100000]);
        
        $jenisCairan = JenisCairan::factory()->create();

        $this->pengajuan = FormPengajuan::factory()->diterima()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'metode_pengambilan' => 'diantar'
        ]);

        $this->pengajuan->parameter()->attach($this->parameter->id);

        $this->pembayaran = Pembayaran::factory()->belumDibayar()->create([
            'id_form_pengajuan' => $this->pengajuan->id,
            'total_biaya' => 500000
        ]);
    }

    public function test_show_menampilkan_halaman_pembayaran_dengan_data_lengkap()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.pembayaran.show', $this->pengajuan->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/pembayaran/Show')
                ->has('pengajuan')
                ->has('pembayaran')
            );
    }

    public function test_show_memblokir_pengajuan_yang_belum_diterima()
    {
        $pengajuanProses = FormPengajuan::factory()->prosesValidasi()->create([
            'id_instansi' => $this->instansi->id
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.pembayaran.show', $pengajuanProses->id));

        $response->assertRedirect()
            ->assertSessionHasErrors(['status_pengajuan']);
    }

    public function test_process_berhasil_dengan_metode_transfer()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('bukti_transfer.jpg', 800, 600)->size(1000);

        $data = [
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => $file
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('customer.pembayaran.process', $this->pengajuan->id), $data);

        $response->assertRedirect(route('customer.dashboard'))
            ->assertSessionHas('message', 'Pembayaran Berhasil.');

        $this->assertDatabaseHas('pembayaran', [
            'id_form_pengajuan' => $this->pengajuan->id,
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'diproses'
        ]);
    }

    public function test_process_berhasil_dengan_metode_tunai()
    {
        $data = [
            'metode_pembayaran' => 'tunai'
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('customer.pembayaran.process', $this->pengajuan->id), $data);

        $response->assertRedirect(route('customer.dashboard'))
            ->assertSessionHas('message', 'Pembayaran Berhasil.');

        $this->assertDatabaseHas('pembayaran', [
            'id_form_pengajuan' => $this->pengajuan->id,
            'metode_pembayaran' => 'tunai',
            'status_pembayaran' => 'diproses'
        ]);
    }
}