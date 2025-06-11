<?php

namespace Tests\Feature\Pegawai;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PegawaiLaporanKeuanganControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $pegawai;
    protected User $customer;
    protected Pembayaran $pembayaranSelesai;
    protected Pembayaran $pembayaranBelumBayar;
    protected Pembayaran $pembayaranTahunLalu;
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

        // Buat permission
        $permission = Permission::firstOrCreate(
            ['name' => 'laporan keuangan', 'guard_name' => 'web'],
            ['kode_permission' => 'PS-011']
        );

        // Berikan permission kepada role pegawai
        $pegawaiRole->givePermissionTo($permission);

        // Buat user
        $this->pegawai = User::factory()->create();
        $this->pegawai->assignRole($pegawaiRole);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($customerRole);

        // Buat data pendukung
        $this->instansi = Instansi::factory()->create(['id_user' => $this->customer->id]);
        $formPengajuan1 = FormPengajuan::factory()->create(['id_instansi' => $this->instansi->id]);
        $formPengajuan2 = FormPengajuan::factory()->create(['id_instansi' => $this->instansi->id]);
        $formPengajuan3 = FormPengajuan::factory()->create(['id_instansi' => $this->instansi->id]);

        // Buat pembayaran dengan status berbeda dan tanggal berbeda
        $this->pembayaranSelesai = Pembayaran::factory()->selesai()->create([
            'id_form_pengajuan' => $formPengajuan1->id,
            'total_biaya' => 500000,
            'tanggal_pembayaran' => Carbon::now(),
        ]);

        $this->pembayaranBelumBayar = Pembayaran::factory()->belumDibayar()->create([
            'id_form_pengajuan' => $formPengajuan2->id,
            'total_biaya' => 300000,
        ]);

        $this->pembayaranTahunLalu = Pembayaran::factory()->selesai()->create([
            'id_form_pengajuan' => $formPengajuan3->id,
            'total_biaya' => 750000,
            'tanggal_pembayaran' => Carbon::now()->subYear(),
        ]);
    }

    public function test_index_menampilkan_laporan_keuangan_tanpa_filter()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/laporan/Index')
                ->has('laporan_keuangan', 1, fn (Assert $laporan) => $laporan
                    ->where('id', $this->pembayaranSelesai->id)
                    ->where('total_biaya', 500000)
                    ->where('status_pembayaran', 'selesai')
                    ->has('form_pengajuan', fn (Assert $form) => $form
                        ->where('id', $this->pembayaranSelesai->id_form_pengajuan)
                        ->has('instansi', fn (Assert $instansi) => $instansi
                            ->where('nama', $this->instansi->nama)
                            ->etc()
                        )
                        ->etc()
                    )
                    ->etc()
                )
                ->has('total_pemasukan')
                ->has('diagram')
                ->has('tahunTersedia')
            );
    }

    public function test_index_filter_bulanan()
    {
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'bulanan',
                'bulan' => $bulanSekarang,
                'tahun_bulanan' => $tahunSekarang,
            ]));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/laporan/Index')
                ->has('laporan_keuangan', 1) // Hanya pembayaran bulan ini
                ->has('laporan_keuangan.0', fn (Assert $pembayaran) => $pembayaran
                    ->where('id', $this->pembayaranSelesai->id)
                    ->etc()
                )
                ->where('total_pemasukan', '500000')
                ->has('filter', fn (Assert $filter) => $filter
                    ->where('periode', 'bulanan')
                    ->where('bulan', $bulanSekarang)
                    ->where('tahun_bulanan', $tahunSekarang)
                    ->etc()
                )
                ->etc()
            );
    }

    public function test_index_filter_tahunan()
    {
        $tahunSekarang = Carbon::now()->year;

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'tahunan',
                'tahun_tahunan' => $tahunSekarang,
            ]));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/laporan/Index')
                ->has('laporan_keuangan', 1) // Hanya pembayaran tahun ini
                ->where('total_pemasukan', '500000')
                ->has('filter', fn (Assert $filter) => $filter
                    ->where('periode', 'tahunan')
                    ->where('tahun_tahunan', $tahunSekarang)
                    ->etc()
                )
                ->etc()
            );
    }

    public function test_index_filter_rentang_tanggal()
    {
        $tanggalMulai = Carbon::now()->subDays(7)->toDateString();
        $tanggalAkhir = Carbon::now()->addDays(7)->toDateString();

        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'rentang_tanggal',
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_akhir' => $tanggalAkhir,
            ]));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/laporan/Index')
                ->has('laporan_keuangan', 1)
                ->where('total_pemasukan', '500000')
                ->has('filter', fn (Assert $filter) => $filter
                    ->where('periode', 'rentang_tanggal')
                    ->where('tanggal_mulai', $tanggalMulai)
                    ->where('tanggal_akhir', $tanggalAkhir)
                    ->etc()
                )
                ->etc()
            );
    }

    public function test_index_tidak_menampilkan_pembayaran_belum_selesai()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('pegawai/laporan/Index')
                ->has('laporan_keuangan', fn (Assert $list) => $list
                    ->each(fn (Assert $pembayaran) => $pembayaran
                        ->where('status_pembayaran', 'selesai')
                        ->etc()
                    )
                )
                ->etc()
            );

        // Pastikan pembayaran belum bayar tidak ada dalam hasil
        $responseData = $response->getOriginalContent()->getData()['page']['props']['laporan_keuangan'];
        $ids = collect($responseData)->pluck('id')->toArray();
        $this->assertNotContains($this->pembayaranBelumBayar->id, $ids);
    }

    public function test_validasi_filter_bulanan_gagal_tanpa_bulan()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'bulanan',
                'tahun_bulanan' => 2024,
            ]));

        $response->assertSessionHasErrors(['bulan']);
    }

    public function test_validasi_filter_bulanan_gagal_tanpa_tahun()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'bulanan',
                'bulan' => 12,
            ]));

        $response->assertSessionHasErrors(['tahun_bulanan']);
    }

    public function test_validasi_filter_tahunan_gagal_tanpa_tahun()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'tahunan',
            ]));

        $response->assertSessionHasErrors(['tahun_tahunan']);
    }

    public function test_validasi_filter_rentang_tanggal_gagal_tanpa_tanggal_mulai()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'rentang_tanggal',
                'tanggal_akhir' => '2024-12-31',
            ]));

        $response->assertSessionHasErrors(['tanggal_mulai']);
    }

    public function test_validasi_filter_rentang_tanggal_gagal_tanggal_akhir_sebelum_mulai()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'rentang_tanggal',
                'tanggal_mulai' => '2024-12-31',
                'tanggal_akhir' => '2024-01-01',
            ]));

        $response->assertSessionHasErrors(['tanggal_akhir']);
    }

    public function test_validasi_bulan_invalid()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'bulanan',
                'bulan' => 13, // Invalid month
                'tahun_bulanan' => 2024,
            ]));

        $response->assertSessionHasErrors(['bulan']);
    }

    public function test_validasi_tahun_invalid_format()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'tahunan',
                'tahun_tahunan' => 24, // Invalid year format
            ]));

        $response->assertSessionHasErrors(['tahun_tahunan']);
    }

    public function test_diagram_data_struktur_benar()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->has('diagram', fn (Assert $diagram) => $diagram
                    ->has('label')
                    ->has('data', fn (Assert $data) => $data
                        ->where('label', 'Total Pemasukan')
                        ->where('backgroundColor', 'rgba(54, 162, 235, 0.2)')
                        ->where('borderColor', 'rgba(54, 162, 235, 1)')
                        ->where('fill', true)
                        ->where('tension', 0.1)
                        ->has('data')
                    )
                )
                ->etc()
            );
    }

    public function test_tahun_tersedia_berisi_tahun_yang_benar()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->has('tahunTersedia', 2) // Tahun sekarang dan tahun lalu
                ->etc()
            );
    }

    public function test_akses_ditolak_tanpa_permission()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('pegawai.laporan_keuangan.index'));

        $response->assertStatus(403);
    }

    public function test_total_pemasukan_hitung_dengan_benar()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index'));

        $expectedTotal = (string)($this->pembayaranSelesai->total_biaya + $this->pembayaranTahunLalu->total_biaya);

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->where('total_pemasukan', $expectedTotal) // Cast to string
                ->etc()
            );
    }

    public function test_laporan_diurutkan_berdasarkan_tanggal_terbaru()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->has('laporan_keuangan.0', fn (Assert $pembayaran) => $pembayaran
                    ->where('id', $this->pembayaranSelesai->id) // Yang terbaru
                    ->etc()
                )
                ->has('laporan_keuangan.1', fn (Assert $pembayaran) => $pembayaran
                    ->where('id', $this->pembayaranTahunLalu->id) // Yang lama
                    ->etc()
                )
                ->etc()
            );
    }

    public function test_filter_periode_invalid()
    {
        $response = $this->actingAs($this->pegawai)
            ->get(route('pegawai.laporan_keuangan.index', [
                'periode' => 'invalid_periode',
            ]));

        $response->assertSessionHasErrors(['periode']);
    }
}
