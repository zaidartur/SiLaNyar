<?php

namespace Tests\Feature\Pegawai;

use App\Models\ParameterUji;
use App\Models\SubKategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiSubKategoriControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected SubKategori $subKategori;
    protected ParameterUji $parameter;
    protected ParameterUji $parameter2;

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
            ['name' => 'kelola subkategori', 'kode' => 'PS-007'],
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

        // Buat data pendukung
        $this->parameter = ParameterUji::factory()->create();
        $this->parameter2 = ParameterUji::factory()->create();
        
        // Buat subkategori dengan parameter
        $this->subKategori = SubKategori::factory()->create();
        $this->subKategori->parameter()->attach([
            $this->parameter->id => ['baku_mutu' => 'Test Baku Mutu 1'],
            $this->parameter2->id => ['baku_mutu' => 'Test Baku Mutu 2']
        ]);
    }

    public function test_index_menampilkan_daftar_subkategori_dengan_parameter()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.subkategori.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/subkategori/Index')
                ->has('subkategori', 1)
                ->has('subkategori.0', fn (Assert $subkategori) => $subkategori
                    ->where('id', $this->subKategori->id)
                    ->where('nama', $this->subKategori->nama)
                    ->where('kode_subkategori', $this->subKategori->kode_subkategori)
                    ->has('parameter', 2)
                    ->has('parameter.0', fn (Assert $param) => $param
                        ->where('id', $this->parameter->id)
                        ->where('nama_parameter', $this->parameter->nama_parameter)
                        ->has('pivot', fn (Assert $pivot) => $pivot
                            ->where('baku_mutu', 'Test Baku Mutu 1')
                            ->etc()
                        )
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_create_menampilkan_form_tambah_subkategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.subkategori.tambah'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/subkategori/Tambah')
                ->has('parameter', 2)
                ->has('parameter.0', fn (Assert $param) => $param
                    ->where('id', $this->parameter->id)
                    ->where('nama_parameter', $this->parameter->nama_parameter)
                    ->where('satuan', $this->parameter->satuan)
                    ->where('harga', $this->parameter->harga)
                    ->etc()
                )
                ->has('parameter.1', fn (Assert $param) => $param
                    ->where('id', $this->parameter2->id)
                    ->etc()
                )
            );
    }

    public function test_store_membuat_subkategori_berhasil()
    {
        $data = [
            'nama' => 'SubKategori Test Baru',
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => 'Baku Mutu Test 1'
                ],
                [
                    'id' => $this->parameter2->id,
                    'baku_mutu' => 'Baku Mutu Test 2'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/subkategori/store', $data);

        $response->assertRedirect(route('pegawai.subkategori.index'))
            ->assertSessionHas('message', 'SubKategori Berhasil Ditambahkan!');

        $this->assertDatabaseHas('subkategori', [
            'nama' => 'SubKategori Test Baru'
        ]);

        $subKategoriBaru = SubKategori::where('nama', 'SubKategori Test Baru')->first();
        $this->assertDatabaseHas('parameter_subkategori', [
            'id_subkategori' => $subKategoriBaru->id,
            'id_parameter' => $this->parameter->id,
            'baku_mutu' => 'Baku Mutu Test 1'
        ]);
    }

    public function test_store_validasi_nama_unik()
    {
        $data = [
            'nama' => $this->subKategori->nama,
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => 'Test'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/subkategori/store', $data);

        $response->assertSessionHasErrors(['nama']);
    }

    public function test_store_validasi_parameter_wajib()
    {
        $data = [
            'nama' => 'SubKategori Tanpa Parameter'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/subkategori/store', $data);

        $response->assertSessionHasErrors(['parameter']);
    }

    public function test_store_validasi_parameter_tidak_ada()
    {
        $data = [
            'nama' => 'SubKategori Parameter Invalid',
            'parameter' => [
                [
                    'id' => 99999, // ID yang tidak ada
                    'baku_mutu' => 'Test'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/subkategori/store', $data);

        $response->assertSessionHasErrors(['parameter.0.id']);
    }

    public function test_edit_menampilkan_form_edit_subkategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.subkategori.edit', $this->subKategori->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/subkategori/Edit')
                ->has('subkategori', fn (Assert $subkategori) => $subkategori
                    ->where('id', $this->subKategori->id)
                    ->where('nama', $this->subKategori->nama)
                    ->etc()
                )
                ->has('parameter', 2)
                ->has('parameter.0', fn (Assert $param) => $param
                    ->where('id', $this->parameter->id)
                    ->where('nama_parameter', $this->parameter->nama_parameter)
                    ->has('pivot', fn (Assert $pivot) => $pivot
                        ->where('baku_mutu', 'Test Baku Mutu 1')
                        ->etc()
                    )
                    ->etc()
                )
                ->has('parameter.1', fn (Assert $param) => $param
                    ->where('id', $this->parameter2->id)
                    ->has('pivot', fn (Assert $pivot) => $pivot
                        ->where('baku_mutu', 'Test Baku Mutu 2')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_update_subkategori_berhasil()
    {
        $parameterBaru = ParameterUji::factory()->create();

        $data = [
            'nama' => 'SubKategori Updated',
            'parameter' => [
                [
                    'id' => $parameterBaru->id,
                    'baku_mutu' => 'Baku Mutu Updated'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/subkategori/{$this->subKategori->id}/edit", $data);

        $response->assertRedirect(route('pegawai.subkategori.index'))
            ->assertSessionHas('message', 'SubKategori Berhasil Ditambahkan!');

        $this->assertDatabaseHas('subkategori', [
            'id' => $this->subKategori->id,
            'nama' => 'SubKategori Updated'
        ]);

        $this->assertDatabaseHas('parameter_subkategori', [
            'id_subkategori' => $this->subKategori->id,
            'id_parameter' => $parameterBaru->id,
            'baku_mutu' => 'Baku Mutu Updated'
        ]);
    }

    public function test_show_menampilkan_detail_subkategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.subkategori.detail', $this->subKategori->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/subkategori/Detail')
                ->has('subkategori', 1)
                ->has('subkategori.0', fn (Assert $subkategori) => $subkategori
                    ->where('id', $this->subKategori->id)
                    ->where('nama', $this->subKategori->nama)
                    ->has('parameter', 2)
                    ->etc()
                )
            );
    }

    public function test_destroy_subkategori_berhasil()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.subkategori.destroy', $this->subKategori->id));

        $response->assertRedirect(route('pegawai.subkategori.index'))
            ->assertSessionHas('message', 'Kategori Berhasil Didelete!');

        $this->assertDatabaseMissing('subkategori', [
            'id' => $this->subKategori->id
        ]);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.subkategori.index'));

        $response->assertStatus(403);
    }

    public function test_kode_subkategori_otomatis_generated()
    {
        $data = [
            'nama' => 'SubKategori Kode Test',
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => 'Test Kode'
                ]
            ]
        ];

        $this->actingAs($this->pegawai)
            ->post('/pegawai/subkategori/store', $data);

        $subKategoriBaru = SubKategori::where('nama', 'SubKategori Kode Test')->first();
        
        $this->assertNotNull($subKategoriBaru);
        $this->assertMatchesRegularExpression('/^SK-\d{3}$/', $subKategoriBaru->kode_subkategori);
    }

    public function test_validasi_baku_mutu_wajib()
    {
        $data = [
            'nama' => 'SubKategori Baku Mutu Kosong',
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => ''
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/subkategori/store', $data);

        $response->assertSessionHasErrors(['parameter.0.baku_mutu']);
    }
}
