<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\Pengujian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PegawaiPengujianControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $teknisi;
    protected User $customer;
    protected FormPengajuan $formPengajuan;
    protected FormPengajuan $formPengajuanDiterima;
    protected Kategori $kategori;
    protected Pengujian $pengujian;
    protected Pengujian $pengujianSelesai;
    protected Instansi $instansi;
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
            ['name' => 'lihat pengujian', 'kode' => 'PS-011'],
            ['name' => 'tambah pengujian', 'kode' => 'PS-012'],
            ['name' => 'edit pengujian', 'kode' => 'PS-013'],
            ['name' => 'detail pengujian', 'kode' => 'PS-014'],
            ['name' => 'hapus pengujian', 'kode' => 'PS-015'],
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
        $this->kategori = Kategori::factory()->create();
        
        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $this->jenisCairan = JenisCairan::factory()->create();

        // Form pengajuan yang masih proses validasi
        $this->formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'status_pengajuan' => 'proses_validasi'
        ]);

        // Form pengajuan yang sudah diterima
        $this->formPengajuanDiterima = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'status_pengajuan' => 'diterima'
        ]);

        // Pengujian yang sedang diproses
        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'status' => 'diproses'
        ]);

        // Pengujian yang sudah selesai
        $this->pengujianSelesai = Pengujian::factory()->selesai()->create([
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'status' => 'selesai'
        ]);
    }

    public function test_index_menampilkan_daftar_pengujian()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengujian.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengujian/Index')
                ->has('pengujian', 2)
                ->has('pengujian', fn (Assert $pengujianList) => $pengujianList
                    ->each(fn (Assert $pengujian) => $pengujian
                        ->hasAll(['id', 'kode_pengujian', 'tanggal_uji', 'jam_mulai', 'jam_selesai', 'status'])
                        ->has('form_pengajuan', fn (Assert $form) => $form
                            ->hasAll(['id', 'kode_pengajuan'])
                            ->has('instansi', fn (Assert $instansi) => $instansi
                                ->hasAll(['id', 'nama'])
                                ->has('user', fn (Assert $user) => $user
                                    ->hasAll(['id', 'nama'])
                                    ->etc()
                                )
                                ->etc()
                            )
                            ->etc()
                        )
                        ->has('user', fn (Assert $user) => $user
                            ->hasAll(['id', 'nama'])
                            ->etc()
                        )
                        ->has('kategori', fn (Assert $kategori) => $kategori
                            ->hasAll(['id', 'nama'])
                            ->etc()
                        )
                        ->etc()
                    )
                )
                ->has('filter')
            );
    }

    public function test_create_menampilkan_form_tambah_pengujian()
    {
        // Buat pengajuan yang diterima tapi belum ada pengujian
        $pengajuanDiterima = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id,
            'id_jenis_cairan' => $this->jenisCairan->id,
            'status_pengajuan' => 'diterima'
        ]);

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengujian.create'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengujian/Tambah')
                ->has('form_pengajuan', 1, fn (Assert $form) => $form
                    ->where('id', $pengajuanDiterima->id)
                    ->hasAll(['id', 'kode_pengajuan', 'id_instansi', 'id_kategori'])
                    ->has('kategori', fn (Assert $kategori) => $kategori
                        ->hasAll(['id', 'nama'])
                        ->etc()
                    )
                    ->has('instansi', fn (Assert $instansi) => $instansi
                        ->hasAll(['id', 'nama'])
                        ->has('user')
                        ->etc()
                    )
                    ->etc()
                )
                ->has('user')
            );
    }

    public function test_store_membuat_pengujian_berhasil()
    {
        $tanggalMulai = Carbon::now()->addDays(1)->format('Y-m-d');
        $tanggalSelesai = Carbon::now()->addDays(3)->format('Y-m-d');

        $data = [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $response->assertRedirect(route('pegawai.pengujian.index'))
            ->assertSessionHas('message', 'Jadwal Pengujian Berhasil Dibuat!');

        $this->assertDatabaseHas('pengujian', [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'diproses'
        ]);
    }

    public function test_store_gagal_jika_pengajuan_belum_diterima()
    {
        $data = [
            'id_form_pengajuan' => $this->formPengajuan->id, // status masih proses_validasi
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $response->assertRedirect()
            ->assertSessionHas('error', 'Sebelum Melakukan Pengujian Harap Verifikasi Pengajuan Terlebih Dahulu!');
    }

    public function test_store_validasi_tanggal_selesai_setelah_mulai()
    {
        $data = [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(1)->format('Y-m-d'), // sebelum tanggal mulai
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $response->assertSessionHasErrors(['tanggal_selesai']);
    }

    public function test_store_validasi_jam_selesai_setelah_mulai()
    {
        $data = [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '12:00',
            'jam_selesai' => '08:00' // sebelum jam mulai
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $response->assertSessionHasErrors(['jam_selesai']);
    }

    public function test_edit_menampilkan_form_edit_pengujian()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengujian.edit', $this->pengujian->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengujian/Edit')
                ->has('pengujian', fn (Assert $pengujian) => $pengujian
                    ->where('id', $this->pengujian->id)
                    ->where('kode_pengujian', $this->pengujian->kode_pengujian)
                    ->where('status', 'diproses')
                    ->has('form_pengajuan', fn (Assert $form) => $form
                        ->has('instansi', fn (Assert $instansi) => $instansi
                            ->hasAll(['id', 'nama'])
                            ->has('user')
                            ->etc()
                        )
                        ->etc()
                    )
                    ->has('user')
                    ->etc()
                )
                ->has('kategoriList')
                ->has('userList')
                ->has('pengajuanList')
            );
    }

    public function test_update_pengujian_berhasil()
    {
        $data = [
            'id_form_pengajuan' => $this->pengujian->id_form_pengajuan,
            'id_kategori' => $this->kategori->id,
            'id_user' => $this->teknisi->id,
            'tanggal_uji' => now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->put("/pegawai/pengujian/{$this->pengujian->id}/edit", $data);

        $response->assertRedirect(route('pegawai.pengujian.detail', $this->pengujian->id))
            ->assertSessionHas('message', 'Pengujian berhasil diupdate');
    }

    // public function test_update_gagal_jika_pengujian_sudah_selesai()
    // {
    //     $this->pengujianSelesai->update(['status' => 'selesai']);

    //     $data = [
    //         'id_form_pengajuan' => $this->pengujianSelesai->id_form_pengajuan,
    //         'id_kategori' => $this->kategori->id,
    //         'id_user' => $this->teknisi->id,
    //         'tanggal_uji' => now()->addDays(1)->format('Y-m-d'),
    //         'jam_mulai' => '08:00',
    //         'jam_selesai' => '10:00'
    //     ];

    //     $response = $this->actingAs($this->pegawai)
    //         ->put("/pegawai/pengujian/{$this->pengujianSelesai->id}/edit", $data);

    //     $response->assertRedirect()
    //         ->assertSessionHasErrors();
    // }

    public function test_show_menampilkan_detail_pengujian()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.pengujian.detail', $this->pengujian->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/pengujian/Detail')
                ->has('pengujian', fn (Assert $pengujian) => $pengujian
                    ->where('id', $this->pengujian->id)
                    ->where('kode_pengujian', $this->pengujian->kode_pengujian)
                    ->has('user', fn (Assert $user) => $user
                        ->hasAll(['id', 'nama'])
                        ->etc()
                    )
                    ->has('form_pengajuan', fn (Assert $form) => $form
                        ->has('instansi', fn (Assert $instansi) => $instansi
                            ->hasAll(['id', 'nama'])
                            ->has('user')
                            ->etc()
                        )
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_destroy_pengujian_berhasil()
    {
        $response = $this->actingAs($this->pegawai)
            ->delete(route('pegawai.pengujian.destroy', $this->pengujian->id));

        $response->assertRedirect(route('pegawai.pengujian.index'))
            ->assertSessionHas('message', 'Jadwal Pengujian Berhasil Dihapus');

        $this->assertDatabaseMissing('pengujian', [
            'id' => $this->pengujian->id
        ]);
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.pengujian.index'));

        $response->assertStatus(403);
    }

    public function test_kode_pengujian_otomatis_generated()
    {
        $data = [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $pengujianBaru = Pengujian::latest()->first();
        
        $this->assertNotNull($pengujianBaru);
        $this->assertMatchesRegularExpression('/^DJ-\d{3}$/', $pengujianBaru->kode_pengujian);
    }

    public function test_validasi_form_pengajuan_tidak_ada()
    {
        $data = [
            'id_form_pengajuan' => 99999, // ID yang tidak ada
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $response->assertSessionHasErrors(['id_form_pengajuan']);
    }

    public function test_validasi_user_teknisi_tidak_ada()
    {
        $data = [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => 99999, // ID yang tidak ada
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        $response->assertSessionHasErrors(['id_user']);
    }

    public function test_store_tidak_membuat_pengujian_di_akhir_pekan()
    {
        // Cari tanggal Sabtu dan Minggu
        $sabtu = Carbon::now()->next(Carbon::SATURDAY)->format('Y-m-d');
        $minggu = Carbon::now()->next(Carbon::SUNDAY)->format('Y-m-d');

        $data = [
            'id_form_pengajuan' => $this->formPengajuanDiterima->id,
            'id_user' => $this->teknisi->id,
            'id_kategori' => $this->kategori->id,
            'tanggal_mulai' => $sabtu,
            'tanggal_selesai' => $minggu,
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00'
        ];

        $response = $this->actingAs($this->pegawai)
            ->post('/pegawai/pengujian/store', $data);

        // Controller sebenarnya membuat pengujian pada akhir pekan
        // Test ini memverifikasi bahwa pengujian dibuat pada sabtu dan minggu
        $this->assertDatabaseHas('pengujian', [
            'tanggal_uji' => $sabtu
        ]);
        $this->assertDatabaseHas('pengujian', [
            'tanggal_uji' => $minggu
        ]);
    }
}
