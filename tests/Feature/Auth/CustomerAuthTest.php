<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_welcome_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertInertia(fn ($assert) => $assert->component('Welcome'));
    }

    public function test_can_view_registration_page(): void
    {
        $response = $this->get('/registrasi');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customer/registrasi'));
    }

    public function test_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('customer/login'));
    }

    public function test_can_register_as_perorangan(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Test Address',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('customer', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'email' => 'test@example.com',
            'status_verifikasi' => 'diproses',
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
    }

    public function test_can_register_as_instansi(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'instansi',
            'alamat_pribadi' => 'Test Address',
            'kontak_pribadi' => '+6281234567890',
            'nama_instansi' => 'Test Company',
            'tipe_instansi' => 'swasta',
            'alamat_instansi' => 'Company Address',
            'kontak_instansi' => '+6289876543210',
            'email' => 'company@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('customer', [
            'nama' => 'Test User',
            'jenis_user' => 'instansi',
            'nama_instansi' => 'Test Company',
            'tipe_instansi' => 'swasta',
            'email' => 'company@example.com',
            'status_verifikasi' => 'diproses',
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
    }

    public function test_cannot_register_with_invalid_data(): void
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

    public function test_cannot_register_instansi_without_required_fields(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'instansi',
            'alamat_pribadi' => 'Test Address',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors(['nama_instansi', 'tipe_instansi', 'alamat_instansi', 'kontak_instansi']);
    }

    public function test_cannot_register_with_existing_email(): void
    {
        Customer::factory()->create([
            'email' => 'existing@example.com'
        ]);

        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Test Address',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_verified_customer_can_login(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'status_verifikasi' => 'diterima',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect('/dashboard');
    }

    public function test_cannot_login_when_not_verified(): void
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

    public function test_customer_can_logout()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        
        $response = $this->actingAs($customer, 'customer')
                        ->post('logout');

        $this->assertGuest('customer');
        $response->assertRedirect('/login');
    }

    public function test_password_must_be_at_least_8_characters(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_email_must_be_valid_format(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_cannot_login_with_wrong_credentials(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'correct@example.com',
            'password' => Hash::make('correctpassword'),
            'status_verifikasi' => 'diterima'
        ]);

        $response = $this->post('/login', [
            'email' => 'correct@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('customer');
    }

    public function test_cannot_access_dashboard_when_not_authenticated(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_cannot_access_dashboard_when_not_verified(): void
    {
        $customer = Customer::factory()->create([
            'status_verifikasi' => 'diproses'
        ]);

        $response = $this->actingAs($customer, 'customer')
                        ->get('/dashboard');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_password_confirmation_must_match(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_kontak_pribadi_must_be_valid_format(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'kontak_pribadi' => '081234567890',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('kontak_pribadi');
    }
}

