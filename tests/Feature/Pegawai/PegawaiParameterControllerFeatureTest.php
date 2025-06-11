<?php

namespace Tests\Feature\Pegawai;

use App\Models\ParameterUji;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiParameterControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected ParameterUji $parameter;

    public function setUp(): void
    {
        parent::setUp();

        // Konfigurasi Vite untuk testing
        config(['app.asset_url' => null]);

        // Buat role dengan kode_role
        $pegawaiRole = Role::firstOrCreate(
            ['name' => 'pegawai', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002']
        );
        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        // Buat permission dengan kode_permission
        $permissions = [
            ['name' => 'kelola parameter', 'kode' => 'PS-007'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['kode_permission' => $permission['kode']]
            );
        }

        // Berikan permission kepada role pegawai
        $pegawaiRole->givePermissionTo(array_column($permissions, 'name'));

        // Buat user
        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        // Buat data parameter untuk testing
        $this->parameter = ParameterUji::factory()->create([
            'nama_parameter' => 'pH Test',
            'satuan' => 'pH',
            'harga' => 100000
        ]);
    }

    public function test_index_menampilkan_daftar_parameter()
    {
        $parameter2 = ParameterUji::factory()->create([
            'nama_parameter' => 'BOD Test',
            'satuan' => 'mg/L',
            'harga' => 150000
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.parameter.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/parameter/Index')
                ->has('parameter', 2)
                ->has('parameter', fn (Assert $parameterList) => $parameterList
                    ->each(fn (Assert $param) => $param
                        ->hasAll(['id', 'kode_parameter', 'nama_parameter', 'satuan', 'harga', 'created_at', 'updated_at'])
                        ->etc()
                    )
                )
                ->has('filter')
            );
    }

    public function test_index_menampilkan_parameter_terbaru_terlebih_dahulu()
    {
        // Tunggu sebentar untuk memastikan timestamp berbeda
        sleep(1);
        
        $parameterBaru = ParameterUji::factory()->create([
            'nama_parameter' => 'COD Test',
            'satuan' => 'mg/L',
            'harga' => 200000
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.parameter.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/parameter/Index')
                ->has('parameter', 2)
                ->has('parameter.0', fn (Assert $param) => $param
                    ->where('nama_parameter', 'COD Test')
                    ->where('satuan', 'mg/L')
                    ->where('harga', 200000)
                    ->etc()
                )
            );
    }

    public function test_store_membuat_parameter_berhasil()
    {
        $data = [
            'nama_parameter' => 'TSS Test',
            'satuan' => 'mg/L',
            'harga' => 125000
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect(route('pegawai.parameter.index'))
            ->assertSessionHas('message', 'Parameter Berhasil Dibuat!');

        $this->assertDatabaseHas('parameter_uji', [
            'nama_parameter' => 'TSS Test',
            'satuan' => 'mg/L',
            'harga' => 125000
        ]);
    }

    public function test_store_validasi_nama_parameter_required()
    {
        $data = [
            'satuan' => 'mg/L',
            'harga' => 125000
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['nama_parameter']);
    }

    public function test_store_validasi_satuan_required()
    {
        $data = [
            'nama_parameter' => 'Test Parameter',
            'harga' => 125000
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['satuan']);
    }

    public function test_store_validasi_harga_required()
    {
        $data = [
            'nama_parameter' => 'Test Parameter',
            'satuan' => 'mg/L'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['harga']);
    }

    public function test_store_validasi_harga_numeric()
    {
        $data = [
            'nama_parameter' => 'Test Parameter',
            'satuan' => 'mg/L',
            'harga' => 'bukan angka'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['harga']);
    }

    public function test_store_validasi_harga_minimum_nol()
    {
        $data = [
            'nama_parameter' => 'Test Parameter',
            'satuan' => 'mg/L',
            'harga' => -100
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['harga']);
    }

    public function test_store_validasi_nama_parameter_unik()
    {
        $data = [
            'nama_parameter' => $this->parameter->nama_parameter,
            'satuan' => 'mg/L',
            'harga' => 100000
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['nama_parameter']);
    }

    public function test_destroy_parameter_berhasil()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.parameter.destroy', $this->parameter->id));

        $response->assertRedirect(route('pegawai.parameter.index'))
            ->assertSessionHas('message', 'Parameter Berhasil Dihapus!');

        $this->assertDatabaseMissing('parameter_uji', [
            'id' => $this->parameter->id
        ]);
    }

    public function test_destroy_parameter_tidak_ditemukan()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete('/pegawai/parameter/99999');

        $response->assertStatus(404);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.parameter.index'));

        $response->assertStatus(403);
    }

    public function test_akses_store_ditolak_tanpa_permission()
    {
        $data = [
            'nama_parameter' => 'Test Parameter',
            'satuan' => 'mg/L',
            'harga' => 100000
        ];

        $response = $this->actingAs($this->customer)
            ->post('/pegawai/parameter/store', $data);

        $response->assertStatus(403);
    }

    public function test_akses_destroy_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->delete(route('pegawai.parameter.destroy', $this->parameter->id));

        $response->assertStatus(403);
    }

    public function test_kode_parameter_otomatis_generated()
    {
        $data = [
            'nama_parameter' => 'TDS Test',
            'satuan' => 'mg/L',
            'harga' => 125000
        ];

        $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $parameterBaru = ParameterUji::where('nama_parameter', 'TDS Test')->first();
        
        $this->assertNotNull($parameterBaru);
        $this->assertMatchesRegularExpression('/^PR-\d{3}$/', $parameterBaru->kode_parameter);
    }

    public function test_kode_parameter_increment_berurutan()
    {
        // Buat parameter pertama
        $data1 = [
            'nama_parameter' => 'Parameter 1',
            'satuan' => 'mg/L',
            'harga' => 100000
        ];

        $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data1);

        // Buat parameter kedua
        $data2 = [
            'nama_parameter' => 'Parameter 2',
            'satuan' => 'pH',
            'harga' => 150000
        ];

        $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data2);

        $parameter1 = ParameterUji::where('nama_parameter', 'Parameter 1')->first();
        $parameter2 = ParameterUji::where('nama_parameter', 'Parameter 2')->first();

        // Cek bahwa kode parameter berurutan
        $kode1Number = (int)substr($parameter1->kode_parameter, -3);
        $kode2Number = (int)substr($parameter2->kode_parameter, -3);

        $this->assertEquals($kode1Number + 1, $kode2Number);
    }

    public function test_harga_nol_valid()
    {
        $data = [
            'nama_parameter' => 'Parameter Gratis',
            'satuan' => 'unit',
            'harga' => 0
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect(route('pegawai.parameter.index'))
            ->assertSessionHas('message', 'Parameter Berhasil Dibuat!');

        $this->assertDatabaseHas('parameter_uji', [
            'nama_parameter' => 'Parameter Gratis',
            'harga' => 0
        ]);
    }

    public function test_index_dengan_data_kosong()
    {
        // Hapus semua parameter yang ada dengan delete (bukan truncate)
        ParameterUji::query()->delete();

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.parameter.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/parameter/Index')
                ->has('parameter', 0)
                ->has('filter')
            );
    }

    public function test_index_modal_based_operations()
    {
        // Test bahwa halaman index dapat menangani operasi modal
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.parameter.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/parameter/Index')
                ->has('parameter')
                ->has('filter')
            );
    }

    public function test_parameter_format_harga()
    {
        // Test bahwa harga dalam format yang benar
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.parameter.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/parameter/Index')
                ->has('parameter.0', fn (Assert $param) => $param
                    ->where('harga', $this->parameter->harga)
                    ->etc()
                )
            );
    }

    public function test_parameter_dengan_relasi_kosong()
    {
        // Test parameter yang tidak memiliki relasi dengan kategori/subkategori
        $parameterTanpaRelasi = ParameterUji::factory()->create([
            'nama_parameter' => 'Parameter Standalone',
            'satuan' => 'unit',
            'harga' => 50000
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.parameter.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/parameter/Index')
                ->has('parameter', 2)
            );
    }

    public function test_store_dengan_nama_parameter_spesial_karakter()
    {
        $data = [
            'nama_parameter' => 'pH (Derajat Keasaman)',
            'satuan' => 'pH',
            'harga' => 100000
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect(route('pegawai.parameter.index'))
            ->assertSessionHas('message', 'Parameter Berhasil Dibuat!');

        $this->assertDatabaseHas('parameter_uji', [
            'nama_parameter' => 'pH (Derajat Keasaman)',
            'satuan' => 'pH',
            'harga' => 100000
        ]);
    }

    public function test_store_dengan_harga_besar()
    {
        $data = [
            'nama_parameter' => 'Test Mahal',
            'satuan' => 'mg/L',
            'harga' => 999999999
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/parameter/store', $data);

        $response->assertRedirect(route('pegawai.parameter.index'))
            ->assertSessionHas('message', 'Parameter Berhasil Dibuat!');

        $this->assertDatabaseHas('parameter_uji', [
            'nama_parameter' => 'Test Mahal',
            'harga' => 999999999
        ]);
    }
}
