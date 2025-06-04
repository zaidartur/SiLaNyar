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
            'nilai' => 10.5,
            'keterangan' => 'Test'
        ]);
        
        $this->assertTrue($pengujian->parameter_uji->contains($parameter));
        $this->assertEquals(10.5, $pengujian->parameter_uji->first()->pivot->nilai);
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

    #[Test]
    public function memastikan_jam_selesai_lebih_besar_dari_jam_mulai()
    {
        $pengujian = Pengujian::factory()->create();
        
        $jamMulai = Carbon::createFromFormat('H:i', $pengujian->jam_mulai->format('H:i'));
        $jamSelesai = Carbon::createFromFormat('H:i', $pengujian->jam_selesai->format('H:i'));
        
        $this->assertTrue($jamSelesai->gt($jamMulai));
    }


    #[Test]
    public function memastikan_bisa_menambah_multiple_parameter_uji()
    {
        $pengujian = Pengujian::factory()->create();
        $parameters = ParameterUji::factory()->count(3)->create();
        
        $parameterData = $parameters->mapWithKeys(function ($parameter) {
            return [$parameter->id => [
                'nilai' => fake()->randomFloat(2, 0, 100),
                'keterangan' => fake()->sentence()
            ]];
        })->toArray();
        
        $pengujian->parameter_uji()->attach($parameterData);
        
        $this->assertEquals(3, $pengujian->parameter_uji->count());
        foreach ($parameters as $parameter) {
            $this->assertTrue($pengujian->parameter_uji->contains($parameter));
            $this->assertNotNull($pengujian->parameter_uji->find($parameter->id)->pivot->nilai);
            $this->assertNotNull($pengujian->parameter_uji->find($parameter->id)->pivot->keterangan);
        }
    }

    #[Test]
    public function memastikan_pivot_timestamps_parameter_uji_berfungsi()
    {
        $pengujian = Pengujian::factory()->create();
        $parameter = ParameterUji::factory()->create();
        
        $pengujian->parameter_uji()->attach($parameter->id, [
            'nilai' => 10.5,
            'keterangan' => 'Test'
        ]);
        
        $pivot = $pengujian->parameter_uji->first()->pivot;
        $this->assertNotNull($pivot->created_at);
        $this->assertNotNull($pivot->updated_at);
    }

    #[Test]
    public function memastikan_pengujian_terhapus_saat_form_pengajuan_terhapus()
    {
        $formPengajuan = FormPengajuan::factory()->create();
        $pengujian = Pengujian::factory()->create([
            'id_form_pengajuan' => $formPengajuan->id
        ]);
        
        $formPengajuan->delete();
        
        $this->assertDatabaseMissing('pengujian', ['id' => $pengujian->id]);
    }

    #[Test]
    public function memastikan_status_bisa_diupdate()
    {
        $pengujian = Pengujian::factory()->create(['status' => 'diproses']);
        
        $pengujian->status = 'selesai';
        $pengujian->save();
        
        $this->assertEquals('selesai', $pengujian->fresh()->status);
    }

    #[Test]
    public function memastikan_tanggal_uji_valid()
    {
        $tanggalUji = now()->format('Y-m-d');
        $pengujian = Pengujian::factory()->create([
            'tanggal_uji' => $tanggalUji
        ]);
        
        $this->assertEquals($tanggalUji, $pengujian->tanggal_uji->format('Y-m-d'));
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $pengujian = Pengujian::factory()->create();
        
        $this->assertNotNull($pengujian->created_at);
        $this->assertNotNull($pengujian->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $pengujian->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $pengujian->updated_at);
    }
}
