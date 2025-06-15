<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\Jadwal;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiJadwalControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $teknisi;
    protected User $customer;
    protected FormPengajuan $pengajuanDiambil;
    protected FormPengajuan $pengajuanDiantar;
    protected Jadwal $jadwal;
    protected Instansi $instansi;
    protected Kategori $kategori;
    protected JenisCairan $jenisCairan;

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
        $teknisiRole = Role::firstOrCreate(
            ['name' => 'teknisi', 'guard_name' => 'web'],
            ['kode_role' => 'RL-003']
        );
        $customerRole = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );

        // Buat permission dengan kode_permission
        $permissions = [
            ['name' => 'lihat pengambilan', 'kode' => 'PS-001'],
            ['name' => 'tambah pengambilan', 'kode' => 'PS-002'], 
            ['name' => 'edit pengambilan', 'kode' => 'PS-003'],
            ['name' => 'hapus pengambilan', 'kode' => 'PS-004'],
            ['name' => 'detail pengambilan', 'kode' => 'PS-005']
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

        $this->teknisi = User::factory()->create();
        $this->teknisi->assignRole($teknisiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        // Buat data pendukung
        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $this->kategori = Kategori::factory()->create();
        $this->jenisCairan = JenisCairan::factory()->create();

        // Buat form pengajuan dengan metode berbeda
        $this->pengajuanDiambil = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima'
        ]);

        $this->pengajuanDiantar = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'metode_pengambilan' => 'diantar',
            'status_pengajuan' => 'diterima'
        ]);

        // Buat jadwal
        $this->jadwal = Jadwal::factory()->create([
            'id_form_pengajuan' => $this->pengajuanDiambil->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => 'diproses'
        ]);
    }

    public function test_index_menampilkan_daftar_jadwal_dengan_filter()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengambilan/Index')
                ->has('jadwal', 1, fn (Assert $jadwal) => $jadwal
                    ->where('id', $this->jadwal->id)
                    ->where('status', 'diproses')
                    ->has('form_pengajuan', fn (Assert $pengajuan) => $pengajuan
                        ->has('instansi.user')
                        ->etc()
                    )
                    ->has('user')
                    ->etc()
                )
                ->has('filter', fn (Assert $filter) => $filter
                    ->where('status', null)
                    ->where('tanggal', null)
                    ->etc()
                )
            );
    }

    public function test_index_dengan_filter_status()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.index', ['status' => 'diproses']));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengambilan/Index')
                ->has('jadwal', 1)
                ->has('filter', fn (Assert $filter) => $filter
                    ->where('status', 'diproses')
                    ->etc()
                )
            );
    }

    public function test_index_dengan_filter_tanggal()
    {
        $tanggal = Carbon::tomorrow()->format('Y-m-d');
        
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.index', ['waktu_pengambilan' => $tanggal]));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengambilan/Index')
                ->has('jadwal', 1)
                ->has('filter', fn (Assert $filter) => $filter
                    ->where('tanggal', $tanggal)
                    ->etc()
                )
            );
    }

    public function test_create_menampilkan_form_tambah_jadwal()
    {
        // Buat pengajuan dengan metode 'diambil' yang belum memiliki jadwal
        $pengajuanDiambilBaru = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima'
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.create'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengambilan/Tambah')
                ->has('form_pengajuan', 1, fn (Assert $form) => $form
                    ->where('id', $pengajuanDiambilBaru->id)
                    ->where('metode_pengambilan', 'diambil')
                    ->has('instansi')
                    ->etc()
                )
                ->has('user')
            );
    }

    public function test_store_membuat_jadwal_berhasil()
    {
        $pengajuanBaru = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima'
        ]);

        $data = [
            'id_form_pengajuan' => $pengajuanBaru->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'keterangan' => 'Jadwal pengambilan sampel'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengambilan/store', $data);

        $response->assertRedirect(route('pegawai.pengambilan.index'))
            ->assertSessionHas('message', 'Jadwal Berhasil Dibuat!');

        $this->assertDatabaseHas('jadwal', [
            'id_form_pengajuan' => $pengajuanBaru->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'keterangan' => 'Jadwal pengambilan sampel',
            'status' => 'diproses'
        ]);
    }

    public function test_store_gagal_jika_pengajuan_metode_diantar()
    {
        $data = [
            'id_form_pengajuan' => $this->pengajuanDiantar->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'keterangan' => 'Test jadwal'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengambilan/store', $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['metode_pengambilan']);
    }

    public function test_store_validasi_tanggal_masa_lalu()
    {
        $pengajuanBaru = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima'
        ]);

        $data = [
            'id_form_pengajuan' => $pengajuanBaru->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::yesterday()->format('Y-m-d'),
            'keterangan' => 'Test jadwal'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengambilan/store', $data);

        $response->assertSessionHasErrors(['waktu_pengambilan']);
    }

    public function test_show_menampilkan_detail_jadwal()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.detail', $this->jadwal->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengambilan/Detail')
                ->has('jadwal', fn (Assert $jadwal) => $jadwal
                    ->where('id', $this->jadwal->id)
                    ->where('status', 'diproses')
                    ->has('form_pengajuan', fn (Assert $pengajuan) => $pengajuan
                        ->has('instansi.user')
                        ->etc()
                    )
                    ->has('user', fn (Assert $user) => $user
                        ->where('id', $this->teknisi->id)
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_edit_menampilkan_form_untuk_jadwal_yang_belum_selesai()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.edit', $this->jadwal->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengambilan/Edit')
                ->has('jadwal', fn (Assert $jadwal) => $jadwal
                    ->where('id', $this->jadwal->id)
                    ->where('status', 'diproses')
                    ->has('form_pengajuan')
                    ->has('user')
                    ->etc()
                )
                ->has('form_pengajuan')
            );
    }

    public function test_edit_memblokir_jadwal_yang_sudah_selesai()
    {
        $this->jadwal->update(['status' => 'selesai']);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengambilan.edit', $this->jadwal->id));

        $response->assertRedirect()
            ->assertSessionHasErrors(['status']);
    }
    
    public function test_update_jadwal_berhasil()
    {
        $data = [
            'waktu_pengambilan' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'status' => 'selesai',
            'keterangan' => 'Jadwal updated'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pengambilan/{$this->jadwal->id}/edit", $data);

        $response->assertRedirect(route('pegawai.pengambilan.index'))
            ->assertSessionHas('message', 'Jadwal Berhasil Diupdate!');

        $this->assertDatabaseHas('jadwal', [
            'id' => $this->jadwal->id,
            'waktu_pengambilan' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'status' => 'selesai',
            'keterangan' => 'Jadwal updated'
        ]);
    }

    public function test_update_gagal_jika_jadwal_sudah_selesai()
    {
        $this->jadwal->update(['status' => 'selesai']);

        $data = [
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Test update'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pengambilan/{$this->jadwal->id}/edit", $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['status']);
    }

    public function test_update_jadwal_diantar_hanya_mengubah_status()
    {
        $jadwalDiantar = Jadwal::factory()->create([
            'id_form_pengajuan' => $this->pengajuanDiantar->id,
            'id_user' => $this->teknisi->id,
            'status' => 'diproses'
        ]);

        $data = [
            'status' => 'selesai'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pengambilan/{$jadwalDiantar->id}/edit", $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('jadwal', [
            'id' => $jadwalDiantar->id,
            'status' => 'selesai'
        ]);
    }

    public function test_update_validasi_reschedule_jadwal_yang_sudah_lewat()
    {
        $jadwalLama = Jadwal::factory()->create([
            'id_form_pengajuan' => FormPengajuan::factory()->create([
                'id_instansi' => $this->instansi->id,
                'metode_pengambilan' => 'diambil'
            ]),
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::yesterday()->format('Y-m-d'),
            'status' => 'diproses'
        ]);

        $data = [
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => 'Reschedule'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pengambilan/{$jadwalLama->id}/edit", $data);

        $response->assertRedirect()
            ->assertSessionHasErrors(['waktu_pengambilan']);
    }

    public function test_destroy_berhasil_untuk_jadwal_selesai()
    {
        $this->jadwal->update(['status' => 'selesai']);

        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.pengambilan.destroy', $this->jadwal->id));

        $response->assertRedirect(route('pegawai.pengambilan.index'))
            ->assertSessionHas('message', 'Jadwal Berhasil Dihapus!');

        $this->assertDatabaseMissing('jadwal', [
            'id' => $this->jadwal->id
        ]);
    }

    public function test_destroy_gagal_untuk_jadwal_diproses()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.pengambilan.destroy', $this->jadwal->id));

        $response->assertRedirect()
            ->assertSessionHasErrors();

        $this->assertDatabaseHas('jadwal', [
            'id' => $this->jadwal->id
        ]);
    }

    public function test_destroy_gagal_untuk_jadwal_diantar()
    {
        $jadwalDiantar = Jadwal::factory()->create([
            'id_form_pengajuan' => $this->pengajuanDiantar->id,
            'status' => 'selesai'
        ]);

        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.pengambilan.destroy', $jadwalDiantar->id));

        $response->assertRedirect()
            ->assertSessionHasErrors();

        $this->assertDatabaseHas('jadwal', [
            'id' => $jadwalDiantar->id
        ]);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $userTanpaPermission = User::factory()->create();
        $roleCustomer = Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['kode_role' => 'RL-001']
        );
        $userTanpaPermission->assignRole($roleCustomer);

        $response = $this->actingAs($userTanpaPermission)
            ->get(route('pegawai.pengambilan.index'));

        $response->assertStatus(403);
    }

    public function test_kode_pengambilan_otomatis_generated()
    {
        $pengajuanBaru = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'metode_pengambilan' => 'diambil',
            'status_pengajuan' => 'diterima'
        ]);

        $data = [
            'id_form_pengajuan' => $pengajuanBaru->id,
            'id_user' => $this->teknisi->id,
            'waktu_pengambilan' => Carbon::tomorrow()->format('Y-m-d'),
            'keterangan' => 'Test kode otomatis'
        ];

        $this->actingAs($this->pegawai)
            ->post('/pegawai/pengambilan/store', $data);

        $jadwalBaru = Jadwal::where('id_form_pengajuan', $pengajuanBaru->id)->first();
        
        $this->assertNotNull($jadwalBaru);
        $this->assertMatchesRegularExpression('/^JP-\d{3}$/', $jadwalBaru->kode_pengambilan);
    }
}
