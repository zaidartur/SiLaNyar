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
    
    // dikomen karena relasi dengan form pengajuan seingetku ilang
    // #[Test]
    // public function memastikan_relasi_dengan_form_pengajuan_berfungsi()
    // {
    //     $user = User::factory()->create();
    //     $formPengajuan = FormPengajuan::factory()->create([
    //         'id_user' => $user->id
    //     ]);
        
    //     $this->assertTrue($user->form_pengajuan->contains($formPengajuan));
    // }

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

    // #[Test]
    // public function memastikan_relasi_dengan_hasil_uji_histori_berfungsi()
    // {
    //     $user = User::factory()->create();
    //     $histori = HasilUjiHistori::factory()->create([
    //         'id_user' => $user->id
    //     ]);
        
    //     $this->assertTrue($user->hasil_uji_histori->contains($histori));
    // }

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
    public function memastikan_format_nik_valid()
    {
        $user = User::factory()->create();
        
        $this->assertMatchesRegularExpression('/^\d{16}$/', $user->nik);
    }

    #[Test]
    public function memastikan_format_nomor_telepon_valid()
    {
        $user = User::factory()->create();
        
        $this->assertNotEmpty($user->no_telepon);
        $this->assertIsString($user->no_telepon);
    }

    #[Test]
    public function memastikan_format_email_valid()
    {
        $user = User::factory()->create();
        
        $this->assertMatchesRegularExpression(
            '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
            $user->email
        );
    }

    #[Test]
    public function memastikan_rt_rw_dalam_range_valid()
    {
        $user = User::factory()->create();
        
        $this->assertGreaterThanOrEqual(1, $user->rt);
        $this->assertLessThanOrEqual(20, $user->rt);
        $this->assertGreaterThanOrEqual(1, $user->rw);
        $this->assertLessThanOrEqual(10, $user->rw);
    }

    #[Test]
    public function memastikan_format_kode_pos_valid()
    {
        $user = User::factory()->create();
        
        $this->assertMatchesRegularExpression('/^\d{5}$/', (string)$user->kode_pos);
        $this->assertGreaterThanOrEqual(10000, $user->kode_pos);
        $this->assertLessThanOrEqual(99999, $user->kode_pos);
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $user = User::factory()->create();
        
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->updated_at);
    }

    #[Test]
    public function memastikan_bisa_memiliki_multiple_instansi()
    {
        $user = User::factory()->create();
        $instansi = Instansi::factory()
            ->count(3)
            ->create(['id_user' => $user->id]);
        
        $this->assertEquals(3, $user->instansi->count());
    }

}
