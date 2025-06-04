<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Instansi;
use App\Models\FormPengajuan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstansiUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_instansi_dibuat_secara_otomatis()
    {
        $instansi = Instansi::factory()->create();
        
        $this->assertStringStartsWith('IN-', $instansi->kode_instansi);
        $this->assertEquals(6, strlen($instansi->kode_instansi));
    }

    #[Test]
    public function memastikan_kode_instansi_berurutan()
    {
        $instansiPertama = Instansi::factory()->create();
        $instansiKedua = Instansi::factory()->create();
        
        $nomorPertama = (int)substr($instansiPertama->kode_instansi, -3);
        $nomorKedua = (int)substr($instansiKedua->kode_instansi, -3);
        
        $this->assertEquals($nomorPertama + 1, $nomorKedua);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $instansi = new Instansi;
        
        $fillable = [
            'kode_instansi',
            'id_user',
            'nama',
            'tipe',
            'alamat',
            'wilayah',
            'desa_kelurahan',
            'email',
            'no_telepon',
            'posisi_jabatan',
            'departemen_divisi'
        ];
        
        $this->assertEquals($fillable, $instansi->getFillable());
    }

    #[Test]
    public function memastikan_relasi_dengan_user_berfungsi()
    {
        $instansi = Instansi::factory()
            ->for(User::factory(), 'user')
            ->create();
            
        $this->assertInstanceOf(User::class, $instansi->user);
    }

    #[Test]
    public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    {
        $instansi = Instansi::factory()->create();
        $formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id
        ]);
        
        $this->assertTrue($instansi->form_pengajuan->contains($formPengajuan));
    }

    #[Test]
    public function memastikan_tipe_instansi_valid()
    {
        $validTipe = ['swasta', 'pemerintahan', 'pribadi'];
        $instansi = Instansi::factory()->create();
        
        $this->assertTrue(in_array($instansi->tipe, $validTipe));
    }

    #[Test]
    public function memastikan_email_bersifat_unique()
    {
        $instansi = Instansi::factory()->create();
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Instansi::factory()->create([
            'email' => $instansi->email
        ]);
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $instansi = Instansi::factory()->create();
        
        $this->assertNotNull($instansi->created_at);
        $this->assertNotNull($instansi->updated_at);
    }

    #[Test]
    public function memastikan_nama_instansi_tidak_boleh_kosong()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Instansi::factory()->create([
            'nama' => null
        ]);
    }

    #[Test]
    public function memastikan_bisa_memiliki_multiple_form_pengajuan()
    {
        $instansi = Instansi::factory()->create();
        $formPengajuan = FormPengajuan::factory()
            ->count(3)
            ->create(['id_instansi' => $instansi->id]);
        
        $this->assertEquals(3, $instansi->form_pengajuan->count());
        foreach ($formPengajuan as $form) {
            $this->assertTrue($instansi->form_pengajuan->contains($form));
        }
    }

    #[Test]
    public function memastikan_format_email_valid()
    {
        $instansi = Instansi::factory()->create([
            'email' => 'test@example.com'
        ]);
        
        $this->assertMatchesRegularExpression(
            '/^[^@]+@[^@]+\.[^@]+$/',
            $instansi->email
        );
    }

    #[Test]
    public function memastikan_nomor_telepon_tidak_boleh_kosong()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Instansi::factory()->create([
            'no_telepon' => null
        ]);
    }

    #[Test]
    public function memastikan_alamat_tidak_boleh_kosong()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Instansi::factory()->create([
            'alamat' => null
        ]);
    }

    #[Test]
    public function memastikan_instansi_terhapus_saat_user_terhapus()
    {
        $user = User::factory()->create();
        $instansi = Instansi::factory()->create([
            'id_user' => $user->id
        ]);
        
        $user->delete();
        
        $this->assertDatabaseMissing('instansi', [
            'id' => $instansi->id
        ]);
    }

    #[Test]
    public function memastikan_form_pengajuan_terhapus_saat_instansi_terhapus()
    {
        $instansi = Instansi::factory()->create();
        $formPengajuan = FormPengajuan::factory()->create([
            'id_instansi' => $instansi->id
        ]);
        
        $instansi->delete();
        
        $this->assertDatabaseMissing('form_pengajuan', [
            'id' => $formPengajuan->id
        ]);
    }

    #[Test]
    public function memastikan_data_instansi_bisa_diupdate()
    {
        $instansi = Instansi::factory()->create();
        
        $namaUpdate = 'Nama Instansi Update';
        $instansi->update(['nama' => $namaUpdate]);
        
        $this->assertEquals($namaUpdate, $instansi->fresh()->nama);
    }

    #[Test]
    public function memastikan_wilayah_dan_desa_kelurahan_tidak_boleh_kosong()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Instansi::factory()->create([
            'wilayah' => null,
            'desa_kelurahan' => null
        ]);
    }
}
