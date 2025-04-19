<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Http\Controllers\Auth\Customer\RegisteredUserController;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/registrasi');

        $response->assertStatus(200);
    }

    public function test_new_personal_customers_can_register()
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Jl. Test No. 123',
            'kontak_pribadi' => '+628123456789',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar Harap Tunggu Verifikasi Data!');
        $this->assertDatabaseHas('customer', [
            'email' => 'test@example.com',
            'jenis_user' => 'perorangan',
        ]);
    }

    public function test_new_institution_customers_can_register()
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'instansi',
            'alamat_pribadi' => 'Jl. Test No. 123',
            'kontak_pribadi' => '+628123456789',
            'nama_instansi' => 'Test Corporation',
            'tipe_instansi' => 'swasta',
            'alamat_instansi' => 'Jl. Corporate No. 456',
            'kontak_instansi' => '+628987654321',
            'email' => 'corp@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar Harap Tunggu Verifikasi Data!');
        $this->assertDatabaseHas('customer', [
            'email' => 'corp@example.com',
            'jenis_user' => 'instansi',
            'nama_instansi' => 'Test Corporation',
            'tipe_instansi' => 'swasta',
        ]);
    }

    public function test_registration_validation_rules()
    {
        // Test required field validation
        $response = $this->post('/registrasi', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSessionHasErrors(['nama', 'jenis_user', 'kontak_pribadi']);
        
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Jl. Test No. 123',
            'kontak_pribadi' => '+628123456789',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSessionHasErrors('email');

        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Jl. Test No. 123',
            'kontak_pribadi' => '+628123456789',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different-password',
        ]);
        $response->assertSessionHasErrors('password');
    }

    public function test_email_must_be_unique()
    {
        Customer::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Jl. Test No. 123',
            'kontak_pribadi' => '+628123456789',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
