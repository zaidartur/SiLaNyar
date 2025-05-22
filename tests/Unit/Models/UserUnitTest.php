<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Aduan;
use App\Models\Instansi;
use App\Models\Pengujian;
use App\Models\FormPengajuan;
use App\Models\HasilUjiHistori;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $user = new User;
        
        $fillable = [
            'nama',
            'nik',
            'tanggal_lahir',
            'rt',
            'rw',
            'kode_pos',
            'alamat',
            'username',
            'no_telepon',
            'email',
        ];
        
        $this->assertEquals($fillable, $user->getFillable());
    }

    #[Test]
    public function memastikan_tanggal_lahir_berupa_date()
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->tanggal_lahir);
    }

    #[Test]
    public function memastikan_rt_rw_dan_kode_pos_berupa_integer()
    {
        $user = User::factory()->create();
        
        $this->assertIsInt($user->rt);
        $this->assertIsInt($user->rw);
        $this->assertIsInt($user->kode_pos);
    }

    #[Test]
    public function memastikan_nik_bersifat_unique()
    {
        $userPertama = User::factory()->create();
        
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        
        User::factory()->create([
            'nik' => $userPertama->nik
        ]);
    }

    #[Test]
    public function memastikan_email_bersifat_unique()
    {
        $userPertama = User::factory()->create();
        
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        
        User::factory()->create([
            'email' => $userPertama->email
        ]);
    }

    #[Test]
    public function memastikan_username_bersifat_unique()
    {
        $userPertama = User::factory()->create();
        
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        
        User::factory()->create([
            'username' => $userPertama->username
        ]);
    }

    #[Test]
    public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    {
        $user = User::factory()->create();
        $formPengajuan = FormPengajuan::factory()->create([
            'id_user' => $user->id
        ]);
        
        $this->assertTrue($user->form_pengajuan->contains($formPengajuan));
    }

    #[Test]
    public function memastikan_relasi_dengan_instansi_berfungsi()
    {
        $user = User::factory()->create();
        $instansi = Instansi::factory()->create([
            'id_user' => $user->id
        ]);
        
        $this->assertTrue($user->instansi->contains($instansi));
    }

    #[Test]
    public function memastikan_relasi_dengan_pengujian_berfungsi()
    {
        $user = User::factory()->create();
        $pengujian = Pengujian::factory()->create([
            'id_user' => $user->id
        ]);
        
        $this->assertTrue($user->pengujian->contains($pengujian));
    }

    #[Test]
    public function memastikan_relasi_dengan_hasil_uji_histori_berfungsi()
    {
        $user = User::factory()->create();
        $histori = HasilUjiHistori::factory()->create([
            'id_user' => $user->id
        ]);
        
        $this->assertTrue($user->hasil_uji_histori->contains($histori));
    }

    #[Test]
    public function memastikan_relasi_dengan_aduan_berfungsi()
    {
        $user = User::factory()->create();
        $aduan = Aduan::factory()->create([
            'id_user' => $user->id
        ]);
        
        $this->assertTrue($user->aduan->contains($aduan));
    }

    #[Test]
    public function memastikan_guard_name_default_adalah_web()
    {
        $user = User::factory()->create();
        
        // Test guard name property langsung
        $this->assertEquals('web', $user->getDefaultGuardName());
    }
}
