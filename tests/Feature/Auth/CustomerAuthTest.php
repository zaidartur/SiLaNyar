<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_registration_page(): void
    {
        $response = $this->get('/registrasi');
        $response->assertStatus(200);
    }

    public function test_customer_can_register_as_perorangan(): void
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
            'email' => 'test@example.com',
            'jenis_user' => 'perorangan',
        ]);
        
        $response->assertRedirect();
    }

    public function test_customer_can_register_as_instansi(): void
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
            'email' => 'company@example.com',
            'jenis_user' => 'instansi',
            'nama_instansi' => 'Test Company',
        ]);
        
        $response->assertRedirect();
    }

    public function test_customer_cannot_access_dashboard_when_not_verified(): void
    {
        $customer = Customer::factory()->create([
            'status_verifikasi' => 'diproses'
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email' => 'Akun Anda Belum Diverifikasi Oleh Admin']);
    }

    public function test_verified_customer_can_login(): void
    {
        $customer = Customer::factory()->create([
            'status_verifikasi' => 'diterima'
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect('/dashboard');
    }

    public function test_customer_can_logout(): void
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create([
            'status_verifikasi' => 'diterima'
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/logout');

        $this->assertGuest('customer');
        $response->assertRedirect('/');
    }

    public function test_customer_cannot_register_with_invalid_phone(): void
    {
        $response = $this->post('/registrasi', [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Test Address',
            'kontak_pribadi' => '12345', // Invalid format
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['kontak_pribadi']);
    }

    public function test_customer_cannot_register_with_existing_email(): void
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
}
