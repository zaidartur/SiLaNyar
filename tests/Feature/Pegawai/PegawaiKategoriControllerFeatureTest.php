<?php

namespace Tests\Feature\Pegawai;

use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\SubKategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiKategoriControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected Kategori $kategori;
    protected Kategori $kategoriDenganParameter;
    protected ParameterUji $parameter;
    protected SubKategori $subKategori;

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
            ['name' => 'kelola kategori', 'kode' => 'PS-006'],
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
        $this->subKategori = SubKategori::factory()->create();
        $this->parameter = ParameterUji::factory()->create();
        
        // Buat kategori dengan subkategori (parameter akan diambil dari subkategori)
        $this->kategori = Kategori::factory()->create();
        $this->kategori->subkategori()->attach($this->subKategori->id);
        $this->subKategori->parameter()->attach($this->parameter->id, ['baku_mutu' => 'Test Baku Mutu Sub']);

        // Buat kategori dengan parameter langsung (tanpa subkategori)
        $this->kategoriDenganParameter = Kategori::factory()->create();
        $this->kategoriDenganParameter->parameter()->attach($this->parameter->id, ['baku_mutu' => 'Test Baku Mutu Direct']);
    }

    public function test_index_menampilkan_daftar_kategori_dengan_subkategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.kategori.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/kategori/Index')
                ->has('kategori', 2)
                ->has('kategori', fn (Assert $kategoriList) => $kategoriList
                    ->each(fn (Assert $kategori) => $kategori
                        ->hasAll(['id', 'kode_kategori', 'nama', 'harga', 'subkategori', 'parameter'])
                        ->etc()
                    )
                )
            );
    }

    public function test_index_menampilkan_kategori_dengan_parameter_dari_subkategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.kategori.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/kategori/Index')
                ->has('kategori.0', fn (Assert $kategoriItem) => $kategoriItem
                    ->where('id', $this->kategori->id)
                    ->has('parameter', 1, fn (Assert $param) => $param
                        ->where('id', $this->parameter->id)
                        ->where('nama_parameter', $this->parameter->nama_parameter)
                        ->where('baku_mutu', 'Test Baku Mutu Sub')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_index_menampilkan_kategori_dengan_parameter_langsung()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.kategori.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/kategori/Index')
                ->has('kategori.1', fn (Assert $kategoriItem) => $kategoriItem
                    ->where('id', $this->kategoriDenganParameter->id)
                    ->has('parameter', 1, fn (Assert $param) => $param
                        ->where('id', $this->parameter->id)
                        ->where('nama_parameter', $this->parameter->nama_parameter)
                        ->where('baku_mutu', 'Test Baku Mutu Direct')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_create_menampilkan_form_tambah_kategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.kategori.tambah'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/kategori/Tambah')
                ->has('parameter', 1, fn (Assert $param) => $param
                    ->where('id', $this->parameter->id)
                    ->where('nama_parameter', $this->parameter->nama_parameter)
                    ->etc()
                )
                ->has('subkategori', 1, fn (Assert $sub) => $sub
                    ->where('id', $this->subKategori->id)
                    ->etc()
                )
            );
    }

    public function test_store_membuat_kategori_dengan_parameter_berhasil()
    {
        $data = [
            'nama' => 'Kategori Test Baru',
            'harga' => 250000,
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => 'Baku Mutu Test'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $response->assertRedirect(route('pegawai.kategori.index'))
            ->assertSessionHas('message', 'Kategori Berhasil Ditambahkan!');

        $this->assertDatabaseHas('kategori', [
            'nama' => 'Kategori Test Baru',
            'harga' => 250000
        ]);

        $kategoriBaru = Kategori::where('nama', 'Kategori Test Baru')->first();
        $this->assertDatabaseHas('parameter_kategori', [
            'id_kategori' => $kategoriBaru->id,
            'id_parameter' => $this->parameter->id,
            'baku_mutu' => 'Baku Mutu Test'
        ]);
    }

    public function test_store_membuat_kategori_dengan_subkategori_berhasil()
    {
        $data = [
            'nama' => 'Kategori Test Sub',
            'harga' => 300000,
            'subkategori' => [$this->subKategori->id]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $response->assertRedirect(route('pegawai.kategori.index'))
            ->assertSessionHas('message', 'Kategori Berhasil Ditambahkan!');

        $this->assertDatabaseHas('kategori', [
            'nama' => 'Kategori Test Sub',
            'harga' => 300000
        ]);

        $kategoriBaru = Kategori::where('nama', 'Kategori Test Sub')->first();
        $this->assertDatabaseHas('kategori_subkategori', [
            'id_kategori' => $kategoriBaru->id,
            'id_subkategori' => $this->subKategori->id
        ]);
    }

    public function test_store_gagal_tanpa_parameter_dan_subkategori()
    {
        $data = [
            'nama' => 'Kategori Kosong',
            'harga' => 150000
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['subkategori']);
    }

    public function test_store_validasi_nama_unik()
    {
        $data = [
            'nama' => $this->kategori->nama,
            'harga' => 200000,
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => 'Test'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $response->assertSessionHasErrors(['nama']);
    }

    public function test_store_validasi_harga_negatif()
    {
        $data = [
            'nama' => 'Kategori Harga Negatif',
            'harga' => -100,
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => 'Test'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $response->assertSessionHasErrors(['harga']);
    }

    public function test_show_menampilkan_detail_kategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.kategori.detail', $this->kategori->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/kategori/Detail')
                ->has('kategori', 1, fn (Assert $kategori) => $kategori
                    ->where('id', $this->kategori->id)
                    ->where('nama', $this->kategori->nama)
                    ->has('subkategori')
                    ->has('parameter')
                    ->etc()
                )
            );
    }

    public function test_edit_menampilkan_form_edit_kategori()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.kategori.edit', $this->kategoriDenganParameter->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/kategori/Edit')
                ->has('kategori', fn (Assert $kategori) => $kategori
                    ->where('id', $this->kategoriDenganParameter->id)
                    ->where('nama', $this->kategoriDenganParameter->nama)
                    ->where('harga', $this->kategoriDenganParameter->harga)
                    ->etc()
                )
                ->has('subkategori', 1, fn (Assert $sub) => $sub
                    ->where('id', $this->subKategori->id)
                    ->etc()
                )
                ->has('parameter', 1, fn (Assert $param) => $param
                    ->where('id', $this->parameter->id)
                    ->has('pivot', fn (Assert $pivot) => $pivot
                        ->where('baku_mutu', 'Test Baku Mutu Direct')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_update_kategori_berhasil()
    {
        $parameterBaru = ParameterUji::factory()->create();

        $data = [
            'nama' => 'Kategori Updated',
            'harga' => 400000,
            'parameter' => [
                [
                    'id' => $parameterBaru->id,
                    'baku_mutu' => 'Baku Mutu Updated'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/kategori/{$this->kategori->id}/edit", $data);

        $response->assertRedirect(route('pegawai.kategori.index'))
            ->assertSessionHas('message', 'Kategori Berhasil Diupdate!');

        $this->assertDatabaseHas('kategori', [
            'id' => $this->kategori->id,
            'nama' => 'Kategori Updated',
            'harga' => 400000
        ]);

        $this->assertDatabaseHas('parameter_kategori', [
            'id_kategori' => $this->kategori->id,
            'id_parameter' => $parameterBaru->id,
            'baku_mutu' => 'Baku Mutu Updated'
        ]);
    }

    public function test_update_kategori_dengan_subkategori()
    {
        $subKategoriBaru = SubKategori::factory()->create();

        $data = [
            'nama' => 'Kategori Sub Updated',
            'harga' => 350000,
            'subkategori' => [$subKategoriBaru->id]
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/kategori/{$this->kategori->id}/edit", $data);

        $response->assertRedirect(route('pegawai.kategori.index'))
            ->assertSessionHas('message', 'Kategori Berhasil Diupdate!');

        $this->assertDatabaseHas('kategori_subkategori', [
            'id_kategori' => $this->kategori->id,
            'id_subkategori' => $subKategoriBaru->id
        ]);
    }

    public function test_update_gagal_tanpa_parameter_dan_subkategori()
    {
        $data = [
            'nama' => 'Kategori Kosong Updated',
            'harga' => 200000
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/kategori/{$this->kategori->id}/edit", $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['subkategori']);
    }

    public function test_destroy_kategori_berhasil()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.kategori.destroy', $this->kategori->id));

        $response->assertRedirect(route('pegawai.kategori.index'))
            ->assertSessionHas('message', 'Kategori Berhasil Didelete!');

        $this->assertDatabaseMissing('kategori', [
            'id' => $this->kategori->id
        ]);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.kategori.index'));

        $response->assertStatus(403);
    }

    public function test_kode_kategori_otomatis_generated()
    {
        $data = [
            'nama' => 'Kategori Kode Test',
            'harga' => 100000,
            'parameter' => [
                [
                    'id' => $this->parameter->id,
                    'baku_mutu' => 'Test Kode'
                ]
            ]
        ];

        $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $kategoriBaru = Kategori::where('nama', 'Kategori Kode Test')->first();
        
        $this->assertNotNull($kategoriBaru);
        $this->assertMatchesRegularExpression('/^DK-\d{3}$/', $kategoriBaru->kode_kategori);
    }

    public function test_validasi_parameter_tidak_ada()
    {
        $data = [
            'nama' => 'Kategori Parameter Invalid',
            'harga' => 150000,
            'parameter' => [
                [
                    'id' => 99999, // ID yang tidak ada
                    'baku_mutu' => 'Test'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $response->assertSessionHasErrors(['parameter.0.id']);
    }

    public function test_validasi_subkategori_tidak_ada()
    {
        $data = [
            'nama' => 'Kategori Sub Invalid',
            'harga' => 150000,
            'subkategori' => [99999] // ID yang tidak ada
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/kategori/store', $data);

        $response->assertSessionHasErrors(['subkategori.0']);
    }
}
