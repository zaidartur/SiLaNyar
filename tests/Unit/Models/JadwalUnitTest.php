<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\FormPengajuan;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JadwalUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_kode_pengambilan_dibuat_secara_otomatis()
    {
        $jadwal = Jadwal::factory()->create();
        
        $this->assertStringStartsWith('JP-', $jadwal->kode_pengambilan);
        $this->assertEquals(6, strlen($jadwal->kode_pengambilan));
    }

    #[Test]
    public function memastikan_kode_pengambilan_berurutan()
    {
        $jadwalPertama = Jadwal::factory()->create();
        $jadwalKedua = Jadwal::factory()->create();
        
        $nomorPertama = (int)substr($jadwalPertama->kode_pengambilan, -3);
        $nomorKedua = (int)substr($jadwalKedua->kode_pengambilan, -3);
        
        $this->assertEquals(1, $nomorPertama);
        $this->assertEquals(2, $nomorKedua);
    }

    #[Test]
    public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    {
        $jadwal = Jadwal::factory()
            ->for(FormPengajuan::factory(), 'form_pengajuan')
            ->create();
            
        $this->assertInstanceOf(FormPengajuan::class, $jadwal->form_pengajuan);
    }

    #[Test]
    public function memastikan_relasi_dengan_user_berfungsi()
    {
        $jadwal = Jadwal::factory()
            ->for(User::factory(), 'user')
            ->create();
            
        $this->assertInstanceOf(User::class, $jadwal->user);
    }

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $jadwal = new Jadwal;
        
        $fillable = [
            'kode_pengambilan',
            'id_form_pengajuan',
            'id_user',
            'waktu_pengambilan',
            'status',
            'keterangan'
        ];
        
        $this->assertEquals($fillable, $jadwal->getFillable());
    }

    #[Test]
    public function memastikan_status_default_adalah_diproses()
    {
        $jadwal = Jadwal::factory()->create();
        $this->assertEquals('diproses', $jadwal->status);
        
        $jadwalBaru = new Jadwal();
        $jadwalBaru->id_form_pengajuan = FormPengajuan::factory()->create()->id;
        $jadwalBaru->id_user = User::factory()->create()->id;
        $jadwalBaru->waktu_pengambilan = now();
        $jadwalBaru->save();
        
        $this->assertEquals('diproses', $jadwalBaru->status);
    }

    #[Test]
    public function memastikan_waktu_pengambilan_berupa_date()
    {
        $jadwal = Jadwal::factory()->create();
        
        $this->assertIsObject($jadwal->waktu_pengambilan);
        $this->assertInstanceOf(\Carbon\Carbon::class, $jadwal->waktu_pengambilan);
    }

    #[Test]
    public function memastikan_keterangan_bisa_null()
    {
        $jadwal = Jadwal::factory()->create([
            'keterangan' => null
        ]);
        
        $this->assertNull($jadwal->keterangan);
    }

    #[Test]
    public function memastikan_kode_pengambilan_bersifat_unique()
    {
        $jadwalPertama = Jadwal::factory()->create();
        
        Jadwal::factory()->create(['kode_pengambilan' => $jadwalPertama->kode_pengambilan]);
        
        $this->assertEquals(1, Jadwal::where('kode_pengambilan', $jadwalPertama->kode_pengambilan)->count());
    }
}
