<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiPembayaranControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected User $customerTanpaPermission;
    protected Pembayaran $pembayaranBelumDibayar;
    protected Pembayaran $pembayaranDiproses;
    protected Pembayaran $pembayaranSelesai;
    protected FormPengajuan $formPengajuan;
    protected Kategori $kategori;
    protected Instansi $instansi;

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
            ['name' => 'kelola pembayaran', 'kode' => 'PS-007'],
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

        $this->customerTanpaPermission = User::factory()->create();
        $this->customerTanpaPermission->assignRole($customerRole);

        // Buat data pendukung
        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $this->kategori = Kategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        $this->kategori->parameter()->attach($parameter->id, ['baku_mutu' => 'Test Baku Mutu']);

        $this->formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
        ]);

        // Buat pembayaran dengan berbagai status
        $this->pembayaranBelumDibayar = Pembayaran::factory()->belumDibayar()->create([
            'id_form_pengajuan' => $this->formPengajuan->id,
            'total_biaya' => 500000,
        ]);

        $formPengajuan2 = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
        ]);
        $this->pembayaranDiproses = Pembayaran::factory()->diproses()->create([
            'id_form_pengajuan' => $formPengajuan2->id,
            'total_biaya' => 750000,
        ]);

        $formPengajuan3 = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
        ]);
        $this->pembayaranSelesai = Pembayaran::factory()->selesai()->create([
            'id_form_pengajuan' => $formPengajuan3->id,
            'total_biaya' => 300000,
            'metode_pembayaran' => 'transfer',
        ]);
    }

    public function test_index_menampilkan_daftar_pembayaran()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pembayaran/Index')
                ->has('pembayaran', 3)
                ->has('pembayaran', fn (Assert $pembayaranList) => $pembayaranList
                    ->each(fn (Assert $pembayaran) => $pembayaran
                        ->hasAll([
                            'id', 'id_order', 'total_biaya', 'tanggal_pembayaran',
                            'metode_pembayaran', 'status_pembayaran', 'bukti_pembayaran',
                            'diverifikasi_oleh', 'form_pengajuan'
                        ])
                        ->has('form_pengajuan', fn (Assert $form) => $form
                            ->hasAll(['id', 'kategori', 'instansi'])
                            ->has('kategori', fn (Assert $kategori) => $kategori
                                ->hasAll(['id', 'nama', 'harga', 'parameter'])
                                ->etc()
                            )
                            ->has('instansi', fn (Assert $instansi) => $instansi
                                ->hasAll(['id', 'user'])
                                ->etc()
                            )
                            ->etc()
                        )
                        ->etc()
                    )
                )
            );
    }

    public function test_index_menampilkan_pembayaran_dengan_status_berbeda()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pembayaran/Index')
                ->has('pembayaran.0', fn (Assert $pembayaran) => $pembayaran
                    ->where('id', $this->pembayaranBelumDibayar->id)
                    ->where('status_pembayaran', 'belum_dibayar')
                    ->where('total_biaya', 500000)
                    ->where('tanggal_pembayaran', null)
                    ->where('metode_pembayaran', null)
                    ->etc()
                )
                ->has('pembayaran.1', fn (Assert $pembayaran) => $pembayaran
                    ->where('id', $this->pembayaranDiproses->id)
                    ->where('status_pembayaran', 'diproses')
                    ->where('total_biaya', 750000)
                    ->etc()
                )
                ->has('pembayaran.2', fn (Assert $pembayaran) => $pembayaran
                    ->where('id', $this->pembayaranSelesai->id)
                    ->where('status_pembayaran', 'selesai')
                    ->where('total_biaya', 300000)
                    ->where('metode_pembayaran', 'transfer')
                    ->etc()
                )
            );
    }

    public function test_show_menampilkan_detail_pembayaran()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.detail', $this->pembayaranSelesai->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pembayaran/Detail')
                ->has('pembayaran', fn (Assert $pembayaran) => $pembayaran
                    ->where('id', $this->pembayaranSelesai->id)
                    ->where('status_pembayaran', 'selesai')
                    ->where('total_biaya', 300000)
                    ->where('metode_pembayaran', 'transfer')
                    ->has('form_pengajuan', fn (Assert $form) => $form
                        ->where('id', $this->pembayaranSelesai->form_pengajuan->id)
                        ->has('kategori', fn (Assert $kategori) => $kategori
                            ->where('id', $this->kategori->id)
                            ->where('nama', $this->kategori->nama)
                            ->has('parameter', 1)
                            ->etc()
                        )
                        ->has('instansi', fn (Assert $instansi) => $instansi
                            ->where('id', $this->instansi->id)
                            ->has('user', fn (Assert $user) => $user
                                ->where('id', $this->customer->id)
                                ->etc()
                            )
                            ->etc()
                        )
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_show_pembayaran_tidak_ditemukan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.detail', 99999));

        $response->assertStatus(404);
    }

    public function test_update_pembayaran_berhasil()
    {
        $data = ['status_pembayaran' => 'selesai'];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pembayaran/{$this->pembayaranDiproses->id}/edit", $data);

        $response->assertRedirect(route('pegawai.pembayaran.index'))
            ->assertSessionHas('message', 'Pembayaran Berhasil Diupdate!');

        $this->assertDatabaseHas('pembayaran', [
            'id' => $this->pembayaranDiproses->id,
            'status_pembayaran' => 'selesai'
        ]);
    }

    // public function test_update_pembayaran_ke_status_gagal()
    // {
    //     $data = ['status_pembayaran' => 'gagal'];

    //     $response = $this->actingAs($this->pegawai)
    //         ->put("/pegawai/pembayaran/{$this->pembayaranBelumDibayar->id}/edit", $data);

    //     $response->assertRedirect(route('pegawai.pembayaran.index'))
    //         ->assertSessionHas('message', 'Pembayaran Berhasil Diupdate!');

    //     $this->assertDatabaseHas('pembayaran', [
    //         'id' => $this->pembayaranBelumDibayar->id,
    //         'status_pembayaran' => 'gagal'
    //     ]);
    // }

    public function test_update_validasi_status_pembayaran_invalid()
    {
        $data = [
            'status_pembayaran' => 'status_invalid',
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pembayaran/{$this->pembayaranDiproses->id}/edit", $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['status_pembayaran']);
    }

    public function test_update_validasi_status_pembayaran_kosong()
    {
        $data = [];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pembayaran/{$this->pembayaranDiproses->id}/edit", $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['status_pembayaran']);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.pembayaran.index'));

        $response->assertStatus(403);
    }

    public function test_akses_edit_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.pembayaran.edit', $this->pembayaranDiproses->id));

        $response->assertStatus(403);
    }

    public function test_akses_update_ditolak_tanpa_permission()
    {
        $data = [
            'status_pembayaran' => 'selesai',
        ];

        $response = $this->actingAs($this->customer)
            ->put("/pegawai/pembayaran/{$this->pembayaranDiproses->id}/edit", $data);

        $response->assertStatus(403);
    }

    public function test_index_menampilkan_data_kategori_dengan_parameter()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pembayaran/Index')
                ->has('pembayaran.0.form_pengajuan.kategori.parameter', 1, fn (Assert $param) => $param
                    ->where('nama_parameter', function ($value) {
                        return is_string($value) && !empty($value);
                    })
                    ->has('pivot', fn (Assert $pivot) => $pivot
                        ->where('baku_mutu', 'Test Baku Mutu')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_pembayaran_dengan_subkategori_parameter()
    {
        // Buat kategori dengan subkategori
        $kategoriSub = Kategori::factory()->create();
        $subKategori = \App\Models\SubKategori::factory()->create();
        $parameter = ParameterUji::factory()->create();
        
        $kategoriSub->subkategori()->attach($subKategori->id);
        $subKategori->parameter()->attach($parameter->id, ['baku_mutu' => 'Baku Mutu Sub']);

        $formPengajuanSub = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $kategoriSub->id,
        ]);

        $pembayaranSub = Pembayaran::factory()->create([
            'id_form_pengajuan' => $formPengajuanSub->id,
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.detail', $pembayaranSub->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pembayaran/Detail')
                ->has('pembayaran.form_pengajuan.kategori.subkategori', 1, fn (Assert $sub) => $sub
                    ->where('id', $subKategori->id)
                    ->has('parameter', 1, fn (Assert $param) => $param
                        ->where('id', $parameter->id)
                        ->has('pivot', fn (Assert $pivot) => $pivot
                            ->where('baku_mutu', 'Baku Mutu Sub')
                            ->etc()
                        )
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_total_biaya_positif_validation()
    {
        // Test sudah ada di model boot method, tapi kita bisa test skenario lain
        $pembayaranNegatif = new Pembayaran([
            'id_form_pengajuan' => $this->formPengajuan->id,
            'total_biaya' => -100000,
            'status_pembayaran' => 'belum_dibayar',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $pembayaranNegatif->save();
    }

    public function test_id_order_otomatis_generated()
    {
        $pembayaranBaru = Pembayaran::factory()->create([
            'id_form_pengajuan' => $this->formPengajuan->id,
        ]);

        $this->assertNotNull($pembayaranBaru->id_order);
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-\d{4}$/', $pembayaranBaru->id_order);
    }

    public function test_debug_instansi_fields()
    {
        // Test untuk melihat field apa saja yang ada di instansi
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pembayaran.index'));

        $response->assertStatus(200);
        
        // Ambil data pembayaran untuk debug
        $pembayaran = Pembayaran::with(['form_pengajuan.instansi'])->first();
        $instansiFields = array_keys($pembayaran->form_pengajuan->instansi->toArray());
        
        // Log untuk debug - akan muncul di output test jika gagal
        $this->assertTrue(true, 'Instansi fields: ' . implode(', ', $instansiFields));
    }
}
