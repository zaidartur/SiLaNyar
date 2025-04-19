<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\Customer\AuthenticatedSessionController;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_customers_can_authenticate_using_the_login_screen()
    {
        $customer = Customer::create([
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'status_verifikasi' => 'diterima'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($customer, 'customer');
        $response->assertRedirect('/dashboard');
    }

    public function test_customers_can_not_authenticate_with_invalid_password()
    {
        $customer = Customer::factory()->create([
            'password' => bcrypt('password'),
            'status_verifikasi' => 'diterima'
        ]);

        $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('customer');
    }

    public function test_customers_can_logout()
    {
        $customer = Customer::factory()->create([
            'password' => bcrypt('password'),
            'status_verifikasi' => 'diterima'
        ]);

        $this->actingAs($customer, 'customer');

        $this->post('/logout')
            ->assertRedirect('/');

        Auth::guard('customer')->logout();
    }

    public function test_verified_customers_can_access_dashboard()
    {
        // Since is_verified doesn't exist, we'll use a different approach
        // You might want to adjust this based on how verification is implemented
        $customer = Customer::factory()->create([
            'password' => bcrypt('password'),
            'status_verifikasi' => 'diterima'
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_unverified_customers_cannot_access_dashboard()
    {
        $customer = Customer::create([
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'status_verifikasi' => 'ditolak'
        ]);

        $this->actingAs($customer, 'customer');
        $response = $this->get('/dashboard');
        
        $response->assertRedirect();
    }
}
