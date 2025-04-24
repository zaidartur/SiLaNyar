<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerAuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_lihat_registrasi_customer()
    {
        $response = $this->get('/registrasi');
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customer/registrasi'));
    }

    public function test_lihat_login_customer()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customer/login'));
    }

    public function test_lihat_welcome()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($assert) => $assert->component('Welcome'));
    }

    public function test_customer_dapat_mendaftar_sebagai_perorangan()
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Wyasana Aji Kusuma Wardana',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Jalan Onta no 1',
            'kontak_pribadi' => '+6285741358179',
            'email' => 'wyasana12@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $this->assertDatabaseHas('customer', [
            'nama' => 'Wyasana Aji Kusuma Wardana',
            'jenis_user' => 'perorangan',
            'email' => 'wyasana12@gmail.com',
            'status_verifikasi' => 'diproses',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
    }

    public function test_customer_dapat_mendaftar_sebagai_instansi()
    {        
        $response = $this->post('/registrasi', [
            'nama' => 'Wyasana Aji Kusuma Wardana',
            'jenis_user' => 'instansi',
            'alamat_pribadi' => 'Jalan Onta no 1',
            'kontak_pribadi' => '+6285741358179',
            'nama_instansi' => 'PT ENTO ID',
            'tipe_instansi' => 'swasta',
            'alamat_instansi' => 'Kampus UNS Mesen',
            'kontak_instansi' => '+6285741358179',
            'email' => 'wyasana12@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $this->assertDatabaseHas('customer', [
            'nama' => 'Wyasana Aji Kusuma Wardana',
            'jenis_user' => 'instansi',
            'nama_instansi' => 'PT ENTO ID',
            'tipe_instansi' => 'swasta',
            'email' => 'wyasana12@gmail.com',
            'status_verifikasi' => 'diproses',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
    }

    public function test_registrasi_ditolak_jika_data_invalid()
    {
        $response = $this->post('/registrasi', [
            'nama' => '',
            'jenis_user' => 'terserah',
            'kontak_pribadi' => 'bukan-nomor',
            'email' => 'bukan-email',
            'password' => 'pendek',
            'password_confirmation' => 'mismatch'
        ]);

        $response->assertSessionHasErrors(['nama', 'jenis_user', 'kontak_pribadi', 'email', 'password']);
    }

    public function test_registrasi_instansi_salah()
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Wyasana Aji Kusuma Wardana',
            'jenis_user' => 'instansi',
            'alamat_pribadi' => 'Jalan Onta 1 no 1',
            'kontak_pribadi' => '+6285741358179',
            'email' => 'wyasana12@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors(['nama_instansi', 'tipe_instansi', 'alamat_instansi', 'kontak_instansi']);
    }

    public function test_customer_dapat_login()
    {
        $customer = Customer::factory()->create([
            'email' => 'wyasana12@gmail.com',
            'password' => bcrypt('password123'),
            'status_verifikasi' => 'diterima',
        ]);

        $response = $this->post('/login', [
            'email' => 'wyasana12@gmail.com',
            'password' => 'password123'
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect('/dashboard');
    }

    public function test_customer_gagal_login()
    {
        $customer = Customer::factory()->create([
            'email' => 'wyasana12@gmail.com',
            'password' => bcrypt('password123'),
            'status_verifikasi' => 'diproses'
        ]);

        $response = $this->post('/login', [
            'email' => 'wyasana12@gmail.com',
            'password' => 'password123'
        ]);

        $this->assertGuest('customer');
        $response->assertSessionHasErrors('email');
    }

    public function test_customer_logout()
    {
        $customer = Customer::factory()->create();
        
        $response = $this->actingAs($customer, 'customer')
                        ->post('logout');

        $this->assertGuest('customer');
        $response->assertRedirect('/login');
    }
}
