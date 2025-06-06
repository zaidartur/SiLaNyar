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
        
        $this->assertContains(
            $pembayaran->status_pembayaran, 
            ['belum_dibayar', 'diproses', 'selesai', 'gagal']
        );
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
            'diverifikasi_oleh',
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
    public function memastikan_format_id_order_valid()
    {
        $pembayaran = Pembayaran::factory()->create();
        
        // Format: ORD-YYYYMMDD-XXXX
        $parts = explode('-', $pembayaran->id_order);
        
        $this->assertEquals('ORD', $parts[0]);
        $this->assertEquals(date('Ymd'), $parts[1]);
        $this->assertEquals(4, strlen($parts[2]));
        $this->assertIsNumeric($parts[2]);
    }

    #[Test]
    public function memastikan_tanggal_pembayaran_sesuai_status()
    {
        // Test pembayaran diproses
        $pembayaranProses = Pembayaran::factory()->diproses()->create();
        $this->assertNull($pembayaranProses->tanggal_pembayaran);
        
        // Test pembayaran selesai
        $pembayaranSelesai = Pembayaran::factory()->selesai()->create();
        $this->assertNotNull($pembayaranSelesai->tanggal_pembayaran);
        
        // Test pembayaran gagal
        $pembayaranGagal = Pembayaran::factory()->gagal()->create();
        $this->assertNull($pembayaranGagal->tanggal_pembayaran);
    }

    #[Test]
    public function memastikan_format_bukti_pembayaran_valid()
    {
        $pembayaran = Pembayaran::factory()->selesai()->state([
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'pembayaran/test-bukti.jpg'
        ])->create();
        
        $this->assertMatchesRegularExpression(
            '/^pembayaran\/[a-zA-Z0-9-_]+\.(jpg|jpeg|png|pdf)$/',
            $pembayaran->bukti_pembayaran
        );
    }

    #[Test]
    public function memastikan_total_biaya_berupa_integer_positif()
    {
        $pembayaran = Pembayaran::factory()->create();
        
        $this->assertIsInt($pembayaran->total_biaya);
        $this->assertGreaterThan(0, $pembayaran->total_biaya);
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $pembayaran = Pembayaran::factory()->create();
        
        $this->assertNotNull($pembayaran->created_at);
        $this->assertNotNull($pembayaran->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $pembayaran->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $pembayaran->updated_at);
    }

    #[Test]
    public function memastikan_pembayaran_terhapus_saat_form_pengajuan_terhapus()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        $pembayaran = Pembayaran::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id
        ]);
        
        $formPengajuan->delete();
        
        $this->assertDatabaseMissing('pembayaran', ['id' => $pembayaran->id]);
    }

    #[Test]
    public function memastikan_verifikator_terisi_saat_pembayaran_selesai()
    {
        $pembayaran = Pembayaran::factory()->selesai()->create();
        
        $this->assertNotNull($pembayaran->diverifikasi_oleh);
    }

    #[Test]
    public function memastikan_verifikator_kosong_saat_pembayaran_belum_selesai()
    {
        $pembayaran = Pembayaran::factory()->belumDibayar()->create();
        
        $this->assertNull($pembayaran->diverifikasi_oleh);
    }

    #[Test]
    public function memastikan_pembayaran_baru_berstatus_belum_dibayar()
    {
        $pembayaran = Pembayaran::factory()->belumDibayar()->create();
        
        $this->assertEquals('belum_dibayar', $pembayaran->status_pembayaran);
        $this->assertNull($pembayaran->tanggal_pembayaran);
        $this->assertNull($pembayaran->metode_pembayaran);
        $this->assertNull($pembayaran->bukti_pembayaran);
        $this->assertNull($pembayaran->diverifikasi_oleh);
    }
}
