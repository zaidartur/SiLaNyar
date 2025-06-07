<?php

namespace Tests\Feature\Customer;

use App\Models\Aduan;
use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\Instansi;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use App\Models\SubKategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HasilUjiControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Instansi $instansi;
    protected FormPengajuan $pengajuan;
    protected Pengujian $pengujian;
    protected HasilUji $hasilUji;
    protected Kategori $kategori;
    protected ParameterUji $parameter;

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
        
        $this->kategori = Kategori::factory()->create();
        $this->parameter = ParameterUji::factory()->create();
        
        $this->pengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $this->instansi->id,
            'id_kategori' => $this->kategori->id
        ]);

        $this->pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $this->pengajuan->id
        ]);

        // Create hasil uji with proper status progression to avoid model validation
        $this->hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf',
            'file_pdf' => 'hasil_uji/test-file.pdf'
        ]);
        
        // Update status properly through model to trigger validation
        $this->hasilUji->update(['status' => 'proses_review']);
        $this->hasilUji->update(['status' => 'proses_peresmian']);
        $this->hasilUji->update(['status' => 'selesai']);

        // Create aduan to make hasil_uji visible in index
        Aduan::factory()->create([
            'id_hasil_uji' => $this->hasilUji->id,
            'perbaikan' => 'Test perbaikan' // Short text to avoid truncation
        ]);

        Storage::fake('public');
    }

    public function test_index_menampilkan_daftar_hasil_uji_dengan_aduan()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/hasil_uji/Index')
                ->has('hasil_uji', 1, fn (Assert $hasil) => $hasil
                    ->where('id', $this->hasilUji->id)
                    ->where('status', 'selesai')
                    ->whereNot('file_pdf', null)
                    ->has('pengujian', fn (Assert $pengujian) => $pengujian
                        ->has('form_pengajuan', fn (Assert $pengajuan) => $pengajuan
                            ->has('jenis_cairan')
                            ->has('kategori')
                            ->has('instansi.user')
                            ->etc()
                        )
                        ->has('user')
                        ->etc()
                    )
                    ->has('aduan')
                    ->etc()
                )
            );
    }

    public function test_index_hanya_menampilkan_hasil_uji_dengan_status_valid()
    {
        // Create hasil uji with invalid status and aduan
        $pengujianDraf = Pengujian::factory()->create([
            'id_form_pengajuan' => FormPengajuan::factory()->create([
                'id_instansi' => $this->instansi->id
            ])->id
        ]);

        $hasilUjiDraf = HasilUji::factory()->create([
            'id_pengujian' => $pengujianDraf->id,
            'status' => 'draf', // Invalid status for display
            'file_pdf' => 'hasil_uji/draf-file.pdf'
        ]);

        Aduan::factory()->create([
            'id_hasil_uji' => $hasilUjiDraf->id,
            'perbaikan' => 'Short text'
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/hasil_uji/Index')
                ->has('hasil_uji', 1) // Only one valid hasil_uji should appear
                ->has('hasil_uji', 1, fn (Assert $hasil) => $hasil
                    ->where('status', 'selesai')
                    ->etc()
                )
            );
    }

    public function test_index_hanya_menampilkan_hasil_uji_dengan_file_pdf()
    {
        // Create hasil uji without file_pdf but with valid status
        $pengujianTanpaFile = Pengujian::factory()->create([
            'id_form_pengajuan' => FormPengajuan::factory()->create([
                'id_instansi' => $this->instansi->id
            ])->id
        ]);

        $hasilUjiTanpaFile = HasilUji::factory()->create([
            'id_pengujian' => $pengujianTanpaFile->id,
            'status' => 'draf',
            'file_pdf' => null // No file
        ]);

        // Progress status properly
        $hasilUjiTanpaFile->update(['status' => 'proses_review']);
        $hasilUjiTanpaFile->update(['status' => 'selesai']);

        Aduan::factory()->create([
            'id_hasil_uji' => $hasilUjiTanpaFile->id,
            'perbaikan' => 'Short text'
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/hasil_uji/Index')
                ->has('hasil_uji', 1) // Only hasil_uji with file_pdf should appear
                ->has('hasil_uji', 1, fn (Assert $hasil) => $hasil
                    ->whereNot('file_pdf', null)
                    ->etc()
                )
            );
    }

    public function test_index_membatasi_hasil_uji_milik_instansi_customer()
    {
        // Create other customer with their own hasil uji
        $otherUser = User::factory()->create();
        $otherUser->assignRole('customer');
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        $otherPengajuan = FormPengajuan::factory()->create(['id_instansi' => $otherInstansi->id]);
        $otherPengujian = Pengujian::factory()->create(['id_form_pengajuan' => $otherPengajuan->id]);
        $otherHasilUji = HasilUji::factory()->create([
            'id_pengujian' => $otherPengujian->id,
            'status' => 'draf',
            'file_pdf' => 'hasil_uji/other-file.pdf'
        ]);
        
        // Progress status properly
        $otherHasilUji->update(['status' => 'proses_review']);
        $otherHasilUji->update(['status' => 'selesai']);
        
        Aduan::factory()->create([
            'id_hasil_uji' => $otherHasilUji->id,
            'perbaikan' => 'Short text'
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('customer/hasil_uji/Index')
                ->has('hasil_uji', 1) // Only own hasil_uji should appear
                ->has('hasil_uji', 1, fn (Assert $hasil) => $hasil
                    ->where('id', $this->hasilUji->id)
                    ->etc()
                )
            );
    }

    public function test_show_memblokir_akses_hasil_uji_milik_customer_lain()
    {
        $otherUser = User::factory()->create();
        $otherInstansi = Instansi::factory()->create(['id_user' => $otherUser->id]);
        $otherPengajuan = FormPengajuan::factory()->create(['id_instansi' => $otherInstansi->id]);
        $otherPengujian = Pengujian::factory()->create(['id_form_pengajuan' => $otherPengajuan->id]);
        $otherHasilUji = HasilUji::factory()->create([
            'id_pengujian' => $otherPengujian->id,
            'status' => 'draf',
            'file_pdf' => 'hasil_uji/other-file.pdf'
        ]);
        
        // Progress status properly
        $otherHasilUji->update(['status' => 'proses_review']);
        $otherHasilUji->update(['status' => 'selesai']);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.detail', $otherHasilUji->id));

        $response->assertStatus(403);
    }

    public function test_show_memblokir_hasil_uji_tanpa_file_pdf()
    {
        $hasilUjiTanpaFile = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf',
            'file_pdf' => null
        ]);
        
        // Progress status properly
        $hasilUjiTanpaFile->update(['status' => 'proses_review']);
        $hasilUjiTanpaFile->update(['status' => 'selesai']);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.detail', $hasilUjiTanpaFile->id));

        $response->assertStatus(433);
    }

    public function test_show_memblokir_hasil_uji_dengan_status_invalid()
    {
        $hasilUjiDraf = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf', // Invalid status for viewing
            'file_pdf' => 'hasil_uji/draf-file.pdf'
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.hasil_uji.detail', $hasilUjiDraf->id));

        $response->assertStatus(433);
    }

    // public function test_show_menggabungkan_parameter_dari_kategori_dan_subkategori()
    // {
    //     // Setup kategori parameter
    //     $paramKategori = ParameterUji::factory()->create(['nama_parameter' => 'pH-Test']);
    //     $this->kategori->parameter()->attach($paramKategori->id, ['baku_mutu' => '6-9']);

    //     // Setup subkategori parameter using actual table structure
    //     $paramSub = ParameterUji::factory()->create(['nama_parameter' => 'DO-Test']);
        
    //     $subkategoriId = DB::table('subkategori')->insertGetId([
    //         'nama' => 'Test SubKategori 2',
    //         'kode_subkategori' => 'TSK-002',
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);
        
    //     // Create relation between kategori and subkategori
    //     DB::table('kategori_subkategori')->insert([
    //         'id_kategori' => $this->kategori->id,
    //         'id_subkategori' => $subkategoriId
    //     ]);
        
    //     DB::table('parameter_subkategori')->insert([
    //         'id_subkategori' => $subkategoriId,
    //         'id_parameter' => $paramSub->id,
    //         'baku_mutu' => '>4 mg/L'
    //     ]);

    //     // Add both to pengujian
    //     DB::table('parameter_pengujian')->insert([
    //         [
    //             'id_pengujian' => $this->pengujian->id,
    //             'id_parameter' => $paramKategori->id,
    //             'nilai' => '7.2',
    //             'keterangan' => 'Normal'
    //         ],
    //         [
    //             'id_pengujian' => $this->pengujian->id,
    //             'id_parameter' => $paramSub->id,
    //             'nilai' => '5.8',
    //             'keterangan' => 'Baik'
    //         ]
    //     ]);

    //     $response = $this->actingAs($this->customer)
    //         ->get(route('customer.hasil_uji.detail', $this->hasilUji->id));

    //     $response->assertStatus(200)
    //         ->assertInertia(fn (Assert $page) => $page
    //             ->component('customer/hasil_uji/Show')
    //             ->has('parameter_pengujian', 2)
    //             ->has('parameter_pengujian', 2, fn (Assert $param) => $param
    //                 ->has('id_parameter')
    //                 ->has('nama_parameter')
    //                 ->has('satuan')
    //                 ->has('nilai')
    //                 ->has('baku_mutu')
    //                 ->has('keterangan')
    //                 ->etc()
    //             )
    //         );
    // }

    public function test_convert_menghasilkan_pdf_download()
    {
        Storage::fake('public');
        Storage::put('public/hasil_uji/test-file.pdf', 'fake pdf content');

        $response = $this->actingAs($this->customer)
            ->get(route('hasil_uji.convert', $this->hasilUji->id));

        $response->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload('Hasil_Uji_' . $this->hasilUji->id . '.pdf');
    }

    public function test_convert_memblokir_hasil_uji_tanpa_file_pdf()
    {
        $hasilUjiTanpaFile = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf',
            'file_pdf' => null
        ]);
        
        // Progress status properly
        $hasilUjiTanpaFile->update(['status' => 'proses_review']);
        $hasilUjiTanpaFile->update(['status' => 'selesai']);

        $response = $this->actingAs($this->customer)
            ->get(route('hasil_uji.convert', $hasilUjiTanpaFile->id));

        $response->assertStatus(433);
    }

    public function test_verifikasi_mengubah_status_dari_proses_review_ke_proses_peresmian()
    {
        $hasilUjiReview = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf',
            'file_pdf' => 'hasil_uji/review-file.pdf'
        ]);
        
        // Progress to proses_review properly
        $hasilUjiReview->update(['status' => 'proses_review']);

        $response = $this->actingAs($this->customer)
            ->put('/customer/hasiluji/' . $hasilUjiReview->id . '/verifikasi', [
                'status' => 'proses_peresmian'
            ]);

        $response->assertRedirect(route('customer.hasil_uji.index'))
            ->assertSessionHas('message', 'Anda Telah Memasuki Fase Proses Peresmian');

        $this->assertDatabaseHas('hasil_uji', [
            'id' => $hasilUjiReview->id,
            'status' => 'proses_peresmian'
        ]);
    }

    public function test_verifikasi_memblokir_perubahan_status_selain_proses_review()
    {
        $response = $this->actingAs($this->customer)
            ->put('/customer/hasiluji/' . $this->hasilUji->id . '/verifikasi', [
                'status' => 'proses_peresmian'
            ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['status']);

        // Status should remain unchanged due to validation in HasilUji model
        $this->assertDatabaseHas('hasil_uji', [
            'id' => $this->hasilUji->id,
            'status' => 'selesai'
        ]);
    }

    public function test_verifikasi_memvalidasi_input_status()
    {
        $hasilUjiReview = HasilUji::factory()->create([
            'id_pengujian' => $this->pengujian->id,
            'status' => 'draf',
            'file_pdf' => 'hasil_uji/review-file.pdf'
        ]);
        
        // Progress to proses_review properly
        $hasilUjiReview->update(['status' => 'proses_review']);

        $response = $this->actingAs($this->customer)
            ->put('/customer/hasiluji/' . $hasilUjiReview->id . '/verifikasi', [
                'status' => 'invalid_status'
            ]);

        $response->assertSessionHasErrors(['status']);
    }
}
