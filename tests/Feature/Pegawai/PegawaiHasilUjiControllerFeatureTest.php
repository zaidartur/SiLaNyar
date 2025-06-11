<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\HasilUjiHistori;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PegawaiHasilUjiControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected Pengujian $pengujian;
    protected HasilUji $hasilUji;
    protected ParameterUji $parameter;
    protected Kategori $kategori;

    public function setUp(): void
    {
        parent::setUp();

        config(['app.asset_url' => null]);

        $pegawaiRole = Role::firstOrCreate(
            ['name' => 'pegawai', 'guard_name' => 'web'],
            ['kode_role' => 'RL-002']
        );

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        $permissions = [
            ['name' => 'lihat hasil uji', 'kode_permission' => 'PS-001'],
            ['name' => 'tambah hasil uji', 'kode_permission' => 'PS-002'],
            ['name' => 'edit hasil uji', 'kode_permission' => 'PS-003'],
            ['name' => 'hapus hasil uji', 'kode_permission' => 'PS-004'],
            ['name' => 'detail hasil uji', 'kode_permission' => 'PS-005'],
            ['name' => 'edit status hasil uji', 'kode_permission' => 'PS-006'],
            ['name' => 'riwayat hasil uji', 'kode_permission' => 'PS-007']
        ];

        $permissionNames = [];
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['kode_permission' => $permission['kode_permission']]
            );
            $permissionNames[] = $perm->name;
        }

        $pegawaiRole->syncPermissions($permissionNames);

        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        $this->kategori = Kategori::factory()->create();
        $this->parameter = ParameterUji::factory()->create();

        // Menghubungkan parameter dengan kategori saja
        $this->kategori->parameter()->attach($this->parameter->id, ['baku_mutu' => 10.5]);

        $instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $jenisCairan = JenisCairan::factory()->create();

        $formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $jenisCairan->id,
            'status_pengajuan' => 'diterima'
        ]);

        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id,
            'id_user' => $this->pegawai->id
        ]);

        $this->hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf'
        ]);

        Storage::fake('local');
    }

    public function test_index_menampilkan_daftar_hasil_uji()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/hasil_uji/Index')
                ->has('hasil_uji', 1, fn (Assert $hasilUji) => $hasilUji
                    ->where('id', $this->hasilUji->id)
                    ->where('status', 'draf')
                    ->has('pengujian', fn (Assert $pengujian) => $pengujian
                        ->where('id', $this->pengujian->id)
                        ->has('form_pengajuan.instansi.user')
                        ->has('user')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_create_menampilkan_form_tambah_hasil_uji()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.tambah'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/hasil_uji/Tambah')
                ->has('pengujianList', 1, fn (Assert $pengujian) => $pengujian
                    ->where('id', $this->pengujian->id)
                    ->where('kode_pengujian', $this->pengujian->kode_pengujian)
                    ->where('id_form_pengajuan', $this->pengujian->id_form_pengajuan)
                    ->etc()
                )
                ->has('pilihPengujian')
                ->where('parameter', [])
            );
    }

    public function test_create_dengan_parameter_pengujian_menampilkan_form_lengkap()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.tambah', ['id_pengujian' => $this->pengujian->id]));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/hasil_uji/Tambah')
                ->has('pilihPengujian', fn (Assert $pengujian) => $pengujian
                    ->where('id', $this->pengujian->id)
                    ->has('form_pengajuan.kategori.parameter')
                    ->has('form_pengajuan.instansi.user')
                    ->has('user')
                    ->etc()
                )
                ->has('parameter', 1, fn (Assert $param) => $param
                    ->where('id', $this->parameter->id)
                    ->where('nama', $this->parameter->nama_parameter)
                    ->where('satuan', $this->parameter->satuan)
                    ->has('baku_mutu')
                    ->etc()
                )
            );
    }

    public function test_store_membuat_hasil_uji_berhasil()
    {
        $data = [
            'id_pengujian' => $this->pengujian->id,
            'hasil' => [
                [
                    'id_parameter' => $this->parameter->id,
                    'nilai' => '15.5',
                    'keterangan' => 'Melebihi baku mutu'
                ]
            ]
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/hasiluji/store', $data);

        $response->assertRedirect(route('pegawai.hasil_uji.index'))
            ->assertSessionHas('message', 'Hasil Uji Berhasil Dibuat!');

        $this->assertDatabaseHas('hasil_uji', [
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf',
            'diupdate_oleh' => $this->pegawai->nama
        ]);

        $this->assertDatabaseHas('parameter_pengujian', [
            'id_pengujian' => $this->pengujian->id,
            'id_parameter' => $this->parameter->id,
            'nilai' => '15.5',
            'keterangan' => 'Melebihi baku mutu'
        ]);
    }

    public function test_edit_menampilkan_form_edit_untuk_status_draf()
    {
        DB::table('parameter_pengujian')->insert([
            'id_pengujian' => $this->pengujian->id,
            'id_parameter' => $this->parameter->id,
            'nilai' => '8.5',
            'keterangan' => 'Memenuhi baku mutu',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.detail', $this->hasilUji->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/hasil_uji/Detail')
                ->has('hasil_uji', fn (Assert $hasilUji) => $hasilUji
                    ->where('id', $this->hasilUji->id)
                    ->where('status', 'draf')
                    ->has('pengujian.form_pengajuan.kategori')
                    ->has('pengujian.form_pengajuan.instansi.user')
                    ->etc()
                )
                ->has('parameter_pengujian', 1, fn (Assert $param) => $param
                    ->where('id_parameter', $this->parameter->id)
                    ->where('nilai', '8.5')
                    ->where('keterangan', 'Memenuhi baku mutu')
                    ->has('baku_mutu')
                    ->etc()
                )
                ->has('parameter', 1)
                ->where('can_edit', true)
            );
    }

    public function test_edit_memblokir_status_selain_draf_dan_revisi()
    {
        DB::table('hasil_uji')
            ->where('id', $this->hasilUji->id)
            ->update(['status' => 'selesai']);
        
        $this->hasilUji->refresh();
        $this->assertEquals('selesai', $this->hasilUji->status);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.detail', $this->hasilUji->id));

        // Detail page tetap bisa diakses, tapi can_edit = false
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/hasil_uji/Detail')
                ->where('can_edit', false)
            );
    }

    public function test_show_menampilkan_detail_hasil_uji()
    {
        // Menambahkan data parameter
        DB::table('parameter_pengujian')->insert([
            'id_pengujian' => $this->pengujian->id,
            'id_parameter' => $this->parameter->id,
            'nilai' => '8.5',
            'keterangan' => 'Memenuhi baku mutu',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.detail', $this->hasilUji->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/hasil_uji/Detail')
                ->has('hasil_uji', fn (Assert $hasilUji) => $hasilUji
                    ->where('id', $this->hasilUji->id)
                    ->where('status', 'draf')
                    ->has('pengujian.form_pengajuan.kategori.parameter')
                    ->has('pengujian.form_pengajuan.instansi.user')
                    ->has('pengujian.user')
                    ->etc()
                )
                ->has('parameter_pengujian', 1, fn (Assert $param) => $param
                    ->where('id_parameter', $this->parameter->id)
                    ->where('nama_parameter', $this->parameter->nama_parameter)
                    ->where('nilai', '8.5')
                    ->where('keterangan', 'Memenuhi baku mutu')
                    ->has('baku_mutu')
                    ->etc()
                )
            );
    }

    public function test_verifikasi_mengubah_status_hasil_uji()
    {
        $data = ['status' => 'proses_review'];

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.hasil_uji.index') . '/verifikasi/' . $this->hasilUji->id, $data);

        $response->assertRedirect(route('pegawai.hasil_uji.index'))
            ->assertSessionHas('message', 'Hasil Uji Berhasil Diupdate');

        $this->assertDatabaseHas('hasil_uji', [
            'id' => $this->hasilUji->id,
            'status' => 'proses_review',
            'diupdate_oleh' => $this->pegawai->nama
        ]);

        $this->assertDatabaseHas('hasil_uji_histori', [
            'id_hasil_uji' => $this->hasilUji->id,
            'status' => 'draf',
            'diupdate_oleh' => $this->pegawai->nama
        ]);
    }

    public function test_verifikasi_mencegah_status_tidak_valid()
    {
        $this->hasilUji->update(['status' => 'selesai']);

        $data = ['status' => 'draf']; // Transisi tidak valid dari 'selesai'

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.hasil_uji.index') . '/verifikasi/' . $this->hasilUji->id, $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['status']);
    }

    public function test_destroy_menghapus_hasil_uji()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.hasil_uji.destroy', $this->hasilUji->id));

        $response->assertRedirect(route('pegawai.hasil_uji.index'))
            ->assertSessionHas('message', 'Hasil Uji Berhasil Dihapus!');

        $this->assertDatabaseMissing('hasil_uji', [
            'id' => $this->hasilUji->id
        ]);
    }

    public function test_verifikasi_status_proses_review_mengisi_proses_review_at()
    {
        $data = ['status' => 'proses_review'];

        $response = $this->actingAs($this->pegawai)
            ->put(route('pegawai.hasil_uji.index') . '/verifikasi/' . $this->hasilUji->id, $data);

        $response->assertRedirect(route('pegawai.hasil_uji.index'));

        $this->hasilUji->refresh();
        $this->assertNotNull($this->hasilUji->proses_review_at);
    }

    public function test_middleware_permission_memblokir_akses_tanpa_permission()
    {
        $userTanpaPermission = User::factory()->create();
        $roleCustomer = Role::where('name', 'customer')->first();
        if ($roleCustomer) {
            $userTanpaPermission->assignRole($roleCustomer);
        }

        $response = $this->actingAs($userTanpaPermission)
            ->get(route('pegawai.hasil_uji.index'));

        $response->assertStatus(403);
    }

    public function test_store_memvalidasi_data_input_yang_diperlukan()
    {
        $data = [
            // Field yang wajib diisi hilang
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/hasiluji/store', $data);

        $response->assertStatus(302);
    }

    public function test_show_menampilkan_detail_hasil_uji_dengan_kemampuan_edit()
    {
        // Menambahkan data parameter
        DB::table('parameter_pengujian')->insert([
            'id_pengujian' => $this->pengujian->id,
            'id_parameter' => $this->parameter->id,
            'nilai' => '8.5',
            'keterangan' => 'Memenuhi baku mutu',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.hasil_uji.detail', $this->hasilUji->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/hasil_uji/Detail')
                ->has('hasil_uji', fn (Assert $hasilUji) => $hasilUji
                    ->where('id', $this->hasilUji->id)
                    ->where('status', 'draf')
                    ->has('pengujian.form_pengajuan.kategori.parameter')
                    ->has('pengujian.form_pengajuan.instansi.user')
                    ->has('pengujian.user')
                    ->etc()
                )
                ->has('parameter_pengujian', 1, fn (Assert $param) => $param
                    ->where('id_parameter', $this->parameter->id)
                    ->where('nama_parameter', $this->parameter->nama_parameter)
                    ->where('nilai', '8.5')
                    ->where('keterangan', 'Memenuhi baku mutu')
                    ->has('baku_mutu')
                    ->etc()
                )
                ->has('parameter', 1)
                ->where('can_edit', true)
            );
    }
}