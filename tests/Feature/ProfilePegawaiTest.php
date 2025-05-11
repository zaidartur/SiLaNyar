<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

class ProfilePegawaiTest extends TestCase
{
    use RefreshDatabase;

    protected $pegawai;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->pegawai = Pegawai::factory()->create([
            'password' => Hash::make('password123'),
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now()
        ]);
    }

    // public function test_pegawai_bisa_melihat_halaman_profil()
    // {
    //     $response = $this->actingAs($this->pegawai, 'pegawai')
    //         ->get(route('pegawai.profile'));

    //     $response->assertStatus(200)
    //         ->assertInertia(fn (Assert $page) => $page
    //             ->component('pegawai/profile/show')
    //             ->has('pegawai', fn (Assert $pegawai) => $pegawai
    //                 ->has('id')
    //                 ->has('nama')
    //                 ->has('email')
    //                 ->has('jabatan')
    //                 ->etc()
    //             )
    //         );
    // }

    public function test_pegawai_bisa_mengupdate_profil()
    {
        $updateData = [
            'nama' => 'Nama Pegawai Baru',
            'jabatan' => 'Analis Kimia',
            'jenis_kelamin' => 'laki-laki',
            'no_telepon' => '+6281234567890',
            'email' => 'pegawai.baru@example.com'
        ];

        $response = $this->actingAs($this->pegawai, 'pegawai')
            ->put('/pegawai/profile/update', $updateData);

        $this->pegawai->refresh();

        $response->assertRedirect(route('pegawai.profile'));
        $this->assertEquals($updateData['nama'], $this->pegawai->nama);
        $this->assertEquals($updateData['jabatan'], $this->pegawai->jabatan);
    }

    public function test_validasi_data_profil_pegawai()
    {
        $response = $this->actingAs($this->pegawai, 'pegawai')
            ->from('/pegawai/profile/edit')
            ->put('/pegawai/profile/update', [
                'nama' => '', // nama kosong
                'jabatan' => '', // jabatan kosong
                'jenis_kelamin' => 'invalid', // jenis kelamin tidak valid
                'no_telepon' => 'bukan-nomor', // format nomor tidak valid
                'email' => 'bukan-email' // format email tidak valid
            ]);

        $response->assertRedirect()
            ->assertSessionHasErrors([
                'nama',
                'jabatan',
                'jenis_kelamin',
                'no_telepon',
                'email'
            ]);
    }

    public function test_pegawai_bisa_menghapus_akun()
    {
        $response = $this->actingAs($this->pegawai, 'pegawai')
            ->delete(route('pegawai.profile.destroy'), [
                'password' => 'password123'
            ]);

        $this->assertNull(Pegawai::find($this->pegawai->id));
        $this->assertGuest('pegawai');
    }

    public function test_password_harus_benar_untuk_hapus_akun()
    {
        $response = $this->actingAs($this->pegawai, 'pegawai')
            ->delete(route('pegawai.profile.destroy'), [
                'password' => 'password_salah'
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertNotNull(Pegawai::find($this->pegawai->id));
    }

    public function test_validasi_jabatan_harus_valid()
    {
        $response = $this->actingAs($this->pegawai, 'pegawai')
            ->from('/pegawai/profile/edit')
            ->put('/pegawai/profile/update', [
                'nama' => 'Nama Valid',
                'jabatan' => 'Jabatan Tidak Valid', // Jabatan yang tidak ada dalam daftar
                'jenis_kelamin' => 'laki-laki',
                'no_telepon' => '+6281234567890',
                'email' => 'valid@example.com'
            ]);

        $response->assertInvalid(['jabatan' => 'The selected jabatan is invalid.']);
    }
}
