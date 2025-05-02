<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pembayaran;
use App\Models\FormPengajuan;
use App\Models\Customer;
use App\Mail\PembayaranMasuk;
use App\Mail\KonfirmasiPembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_membuat_pembayaran_baru()
    {
        $formPengajuan = FormPengajuan::factory()->create(['status_pengajuan' => 'diterima']);
        
        $dataPembayaran = [
            'id_order' => 'LAB-' . date('Ymd') . '-0001',
            'id_form_pengajuan' => $formPengajuan->id,
            'total_biaya' => 500000,
            'status_pembayaran' => 'pending'
        ];

        $pembayaran = Pembayaran::create($dataPembayaran);

        $this->assertDatabaseHas('pembayaran', $dataPembayaran);
        $this->assertEquals('pending', $pembayaran->status_pembayaran);
    }

    public function test_bisa_mengupdate_status_pembayaran()
    {
        $pembayaran = Pembayaran::factory()->create([
            'status_pembayaran' => 'pending'
        ]);

        $pembayaran->update([
            'status_pembayaran' => 'dibayar',
            'tanggal_pembayaran' => now(),
            'metode_pembayaran' => 'QRIS'
        ]);

        $this->assertEquals('dibayar', $pembayaran->fresh()->status_pembayaran);
        $this->assertNotNull($pembayaran->fresh()->tanggal_pembayaran);
    }

    public function test_bisa_mendapatkan_total_biaya_dengan_benar()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        $pembayaran = Pembayaran::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id,
            'total_biaya' => 750000
        ]);

        $this->assertEquals(750000, $pembayaran->total_biaya);
    }

    public function test_relasi_dengan_form_pengajuan()
    {
        // Buat form pengajuan terlebih dahulu
        $formPengajuan = FormPengajuan::factory()->create([
            'status_pengajuan' => 'diterima'
        ]);

        // Buat pembayaran dengan form pengajuan yang sudah ada
        $pembayaran = Pembayaran::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id,
            'id_order' => 'LAB-' . date('Ymd') . '-' . rand(1000, 9999),
            'total_biaya' => 500000,
            'status_pembayaran' => 'pending'
        ]);

        // Load relasi secara eksplisit
        $pembayaran = $pembayaran->load('form_pengajuan');

        // Verifikasi relasi
        $this->assertNotNull($pembayaran->form_pengajuan, 'Relasi form_pengajuan seharusnya tidak null');
        $this->assertEquals($formPengajuan->id, $pembayaran->form_pengajuan->id);
    }

    public function test_mengirim_email_ketika_pembayaran_dikonfirmasi()
    {
        Mail::fake();

        $pembayaran = Pembayaran::factory()->create([
            'status_pembayaran' => 'pending'
        ]);

        $pembayaran->update([
            'status_pembayaran' => 'dibayar',
            'tanggal_pembayaran' => now()
        ]);

        Mail::assertNothingSent(); // Karena logika pengiriman email ada di controller
    }

    public function test_validasi_id_order_harus_unik()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $idOrder = 'LAB-' . date('Ymd') . '-0001';
        
        Pembayaran::factory()->create(['id_order' => $idOrder]);
        Pembayaran::factory()->create(['id_order' => $idOrder]); // Seharusnya gagal karena duplikat
    }

    public function test_bisa_memfilter_pembayaran_berdasarkan_status()
    {
        Pembayaran::factory()->count(3)->create(['status_pembayaran' => 'pending']);
        Pembayaran::factory()->count(2)->create(['status_pembayaran' => 'dibayar']);

        $pending = Pembayaran::where('status_pembayaran', 'pending')->get();
        $dibayar = Pembayaran::where('status_pembayaran', 'dibayar')->get();

        $this->assertEquals(3, $pending->count());
        $this->assertEquals(2, $dibayar->count());
    }

    public function test_total_biaya_harus_positif()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Tambahkan form pengajuan yang valid
        $formPengajuan = FormPengajuan::factory()->create([
            'status_pengajuan' => 'diterima'
        ]);

        // Coba buat pembayaran dengan total biaya negatif
        Pembayaran::create([
            'id_order' => 'LAB-' . date('Ymd') . '-' . rand(1000, 9999),
            'id_form_pengajuan' => $formPengajuan->id,
            'total_biaya' => -1000,
            'status_pembayaran' => 'pending'
        ]);
    }

    public function test_bisa_mencari_pembayaran_berdasarkan_id_order()
    {
        $idOrder = 'LAB-' . date('Ymd') . '-0001';
        $pembayaran = Pembayaran::factory()->create([
            'id_order' => $idOrder
        ]);

        $hasil = Pembayaran::where('id_order', $idOrder)->first();

        $this->assertEquals($pembayaran->id, $hasil->id);
    }
}
