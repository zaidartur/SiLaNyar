<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\HasilUji;
use App\Models\Kategori;
use App\Models\Pengujian;
use App\Models\ParameterUji;
use App\Models\FormPengajuan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PengujianUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_pengujian_dibuat_secara_otomatis()
    {
        $pengujian = Pengujian::factory()->create();
        
        $this->assertStringStartsWith('DJ-', $pengujian->kode_pengujian);
        $this->assertEquals(6, strlen($pengujian->kode_pengujian));
    }

    #[Test]
    public function memastikan_kode_pengujian_berurutan()
    {
        $pengujianPertama = Pengujian::factory()->create();
        $pengujianKedua = Pengujian::factory()->create();
        
        $nomorPertama = (int)substr($pengujianPertama->kode_pengujian, -3);
        $nomorKedua = (int)substr($pengujianKedua->kode_pengujian, -3);
        
        $this->assertEquals(1, $nomorPertama);
        $this->assertEquals(2, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    {
        $pengujian = Pengujian::factory()
            ->for(FormPengajuan::factory(), 'form_pengajuan')
            ->create();
            
        $this->assertInstanceOf(FormPengajuan::class, $pengujian->form_pengajuan);
    }

    #[Test]
    public function memastikan_relasi_dengan_user_berfungsi()
    {
        $pengujian = Pengujian::factory()
            ->for(User::factory(), 'user')
            ->create();
            
        $this->assertInstanceOf(User::class, $pengujian->user);
    }

    #[Test]
    public function memastikan_relasi_dengan_kategori_berfungsi()
    {
        $pengujian = Pengujian::factory()
            ->for(Kategori::factory(), 'kategori')
            ->create();
            
        $this->assertInstanceOf(Kategori::class, $pengujian->kategori);
    }

    #[Test]
    public function memastikan_relasi_dengan_parameter_uji_berfungsi()
    {
        $pengujian = Pengujian::factory()->create();
        $parameter = ParameterUji::factory()->create();
        
        $pengujian->parameter_uji()->attach($parameter->id, [
            'kode_hasil_uji' => 'HU-' . date('my') . '-001',
            'nilai' => 10.5,
            'keterangan' => 'Test'
        ]);
        
        $this->assertTrue($pengujian->parameter_uji->contains($parameter));
        $this->assertEquals(10.5, $pengujian->parameter_uji->first()->pivot->nilai);
    }

    #[Test]
    public function memastikan_relasi_dengan_hasil_uji_berfungsi()
    {
        $pengujian = Pengujian::factory()->create();
        $hasilUji = HasilUji::factory()->create([
            'id_pengujian' => $pengujian->id,
            'kode_hasil_uji' => 'HU-' . date('my') . '-001'
        ]);
        
        $this->assertTrue($pengujian->hasil_uji->contains($hasilUji));
    }

    #[Test]
    public function memastikan_tanggal_uji_berupa_date()
    {
        $pengujian = Pengujian::factory()->create();
        
        $this->assertInstanceOf(Carbon::class, $pengujian->tanggal_uji);
    }

    #[Test]
    public function memastikan_jam_mulai_dan_selesai_berupa_time()
    {
        $pengujian = Pengujian::factory()->create();
        
        $this->assertMatchesRegularExpression('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $pengujian->jam_mulai->format('H:i'));
        $this->assertMatchesRegularExpression('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $pengujian->jam_selesai->format('H:i'));
    }

    #[Test]
    public function memastikan_status_valid()
    {
        $pengujian = Pengujian::factory()->create();
        
        $this->assertContains($pengujian->status, ['diproses', 'selesai']);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $pengujian = new Pengujian;
        
        $fillable = [
            'kode_pengujian',
            'id_form_pengajuan',
            'id_user',
            'id_kategori',
            'tanggal_uji',
            'jam_mulai',
            'jam_selesai',
            'status'
        ];
        
        $this->assertEquals($fillable, $pengujian->getFillable());
    }
}
