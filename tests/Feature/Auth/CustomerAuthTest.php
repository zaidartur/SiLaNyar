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
    //         ->component('customer/registrasi')
    //     );
    // }

    /**
     * Test customer can register as individual
     */
    public function test_customer_can_register_as_individual(): void
    {
        $customerData = [
            'nama' => 'Test User',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Jl. Test No. 123',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/registrasi', $customerData);

        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
        $this->assertDatabaseHas('customer', [
            'email' => 'test@example.com',
            'jenis_user' => 'perorangan',
            'status_verifikasi' => 'diproses',
        ]);
    }

    /**
     * Test customer can register as institution
     */
    public function test_customer_can_register_as_institution(): void
    {
        $customerData = [
            'nama' => 'Test Admin',
            'jenis_user' => 'instansi',
            'kontak_pribadi' => '+6281234567890',
            'nama_instansi' => 'PT Test',
            'tipe_instansi' => 'swasta',
            'alamat_instansi' => 'Jl. Instansi No. 123',
            'kontak_instansi' => '+6289876543210',
            'email' => 'instansi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/registrasi', $customerData);

        $response->assertSessionHas('message', 'Akun Berhasil Terdaftar. Harap Tunggu Verifikasi Admin!');
        $this->assertDatabaseHas('customer', [
            'email' => 'instansi@example.com',
            'jenis_user' => 'instansi',
            'status_verifikasi' => 'diproses',
        ]);
    }

    /**
     * Test login page can be rendered
     */
    // public function test_login_page_can_be_rendered(): void
    // {
    //     $response = $this->get('/login');

    //     $response->assertInertia(fn (Assert $page) => $page
    //         ->component('customer/login')
    //     );
    // }

    /**
     * Test verified customer can login
     */
    public function test_verified_customer_can_login(): void
    {
        $customer = Customer::factory()->verified()->create([
            'email' => 'verified@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'verified@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($customer, 'customer');
        $response->assertRedirect(route('customer.dashboard'));
    }

    /**
     * Test unverified customer cannot login
     */
    public function test_unverified_customer_cannot_login(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password123'),
            'status_verifikasi' => 'diproses',
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('customer.login'));
        $response->assertSessionHasErrors(['email' => 'Akun Anda Belum Diverifikasi Oleh Admin']);
    }

    /**
     * Test customer can logout
     */
    // public function test_customer_can_logout(): void
    // {
    //     $customer = Customer::factory()->verified()->create();

    //     $response = $this->actingAs($customer, 'customer')
    //         ->post('/customer/logout');

    //     $this->assertGuest('customer');
    //     $response->assertRedirect('/');
    // }

    /**
     * Test registration validation rules
     */
    public function test_registration_validation_rules(): void
    {
        $response = $this->post('/registrasi', []);

        $response->assertSessionHasErrors(['nama', 'jenis_user', 'kontak_pribadi', 'email', 'password']);
    }

    /**
     * Test customer cannot login with invalid credentials
     */
    public function test_customer_cannot_login_with_invalid_credentials(): void
    {
        $customer = Customer::factory()->verified()->create();

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }
}
