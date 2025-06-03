<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Pembayaran;
use App\Models\FormPengajuan;
use Illuminate\Database\QueryException;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PembayaranUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_id_order_dibuat_secara_otomatis()
    {
        $pembayaran = Pembayaran::factory()->create();
        
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-\d{4}$/', $pembayaran->id_order);
    }

    #[Test]
    public function memastikan_id_order_bersifat_unique()
    {
        $pembayaranPertama = Pembayaran::factory()->create();
        
        $this->assertEquals(1, Pembayaran::where('id_order', $pembayaranPertama->id_order)->count());
    }

    #[Test]
    public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    {
        $pembayaran = Pembayaran::factory()
            ->for(FormPengajuan::factory(), 'form_pengajuan')
            ->create();
            
        $this->assertInstanceOf(FormPengajuan::class, $pembayaran->form_pengajuan);
    }

    #[Test]
    public function memastikan_total_biaya_tidak_boleh_negatif()
    {
        $this->expectException(QueryException::class);
        
        Pembayaran::factory()->create([
            'total_biaya' => -1000
        ]);
    }

    #[Test]
    public function memastikan_tanggal_pembayaran_berupa_date()
    {
        $pembayaran = Pembayaran::factory()->selesai()->create();
        
        $this->assertInstanceOf(\Carbon\Carbon::class, $pembayaran->tanggal_pembayaran);
    }

    #[Test]
    public function memastikan_status_pembayaran_valid()
    {
        $pembayaran = Pembayaran::factory()->create();
        
        $this->assertContains($pembayaran->status_pembayaran, ['diproses', 'selesai', 'gagal']);
    }

    #[Test]
    public function memastikan_bukti_pembayaran_nullable()
    {
        $pembayaran = Pembayaran::factory()->create([
            'status_pembayaran' => 'diproses',
            'bukti_pembayaran' => null
        ]);
        
        $this->assertNull($pembayaran->bukti_pembayaran);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $pembayaran = new Pembayaran;
        
        $fillable = [
            'id_order',
            'id_form_pengajuan',
            'total_biaya',
            'tanggal_pembayaran',
            'metode_pembayaran',
            'status_pembayaran',
            'bukti_pembayaran',
        ];
        
        $this->assertEquals($fillable, $pembayaran->getFillable());
    }

        #[Test]
    public function memastikan_metode_pembayaran_valid()
    {
        // Test untuk pembayaran yang belum selesai (null)
        $pembayaranBelumSelesai = Pembayaran::factory()->diproses()->create();
        $this->assertNull($pembayaranBelumSelesai->metode_pembayaran);

        // Test untuk pembayaran yang sudah selesai (tunai atau transfer)
        $pembayaranSelesai = Pembayaran::factory()->selesai()->create();
        $this->assertContains($pembayaranSelesai->metode_pembayaran, ['tunai', 'transfer']);
    }

    #[Test]
    public function memastikan_bukti_pembayaran_terisi_saat_status_selesai()
    {
        // Test transfer
        $pembayaranTransfer = Pembayaran::factory()->selesai()->state([
            'metode_pembayaran' => 'transfer'
        ])->create();
        $this->assertNotNull($pembayaranTransfer->bukti_pembayaran);
        $this->assertStringStartsWith('pembayaran/', $pembayaranTransfer->bukti_pembayaran);

        // Test tunai
        $pembayaranTunai = Pembayaran::factory()->selesai()->state([
            'metode_pembayaran' => 'tunai'
        ])->create();
        $this->assertNull($pembayaranTunai->bukti_pembayaran);
    }
}
