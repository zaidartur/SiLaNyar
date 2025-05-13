<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test registration page can be rendered
     */
    // public function test_registration_page_can_be_rendered(): void
    // {
    //     $response = $this->get('/registrasi');

    //     $response->assertInertia(fn (Assert $page) => $page
    //         ->component('customer/Registrasi')
    //     );
    // }

    /**
     * Test customer can register
     */
    public function test_customer_can_register(): void
    {
        $customerData = [
            'nama' => 'Test User',
            'nik' => '1234567890123456',
            'jabatan' => 'Manager',
            'no_telepon' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/registrasi', $customerData);

        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
        $this->assertDatabaseHas('customer', [
            'email' => 'test@example.com',
            'nama' => 'Test User',
            'nik' => '1234567890123456',
            'jabatan' => 'Manager',
        ]);
    }

    /**
     * Test login page can be rendered
     */
    // public function test_login_page_can_be_rendered(): void
    // {
    //     $response = $this->get('/login');

    //     $response->assertInertia(fn (Assert $page) => $page
    //         ->component('customer/Login')
    //     );
    // }

    /**
     * Test verified customer can login
     */
    public function test_verified_customer_can_login(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'verified@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'verified@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated('customer');
    }

    /**
     * Test customer can logout
     */
    public function test_customer_can_logout(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->post('/customer/logout');

        $response->assertRedirect('/');
        $this->assertGuest('customer');
    }

    /**
     * Test registration validation rules
     */
    public function test_registration_validation_rules(): void
    {
        $response = $this->post('/registrasi', []);

        $response->assertSessionHasErrors(['nama', 'nik', 'jabatan', 'no_telepon', 'email', 'password']);
    }

    /**
     * Test customer cannot login with invalid credentials
     */
    public function test_customer_cannot_login_with_invalid_credentials(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }
}
