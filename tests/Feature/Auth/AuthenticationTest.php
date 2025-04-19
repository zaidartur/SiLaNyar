<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertInertia(fn ($assert) => $assert->component('customer/login'));
    }

    public function test_customers_can_authenticate_using_the_login_screen()
    {
        $customer = Customer::factory()->create();

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password', // Assumes the factory uses 'password' as the default
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect('/dashboard');
    }

    public function test_customers_can_not_authenticate_with_invalid_password()
    {
        $customer = Customer::factory()->create();

        $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('customer');
    }

    public function test_customers_can_logout()
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customer');

        $this->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest('customer');
    }

    public function test_verified_customers_can_access_dashboard()
    {
        $customer = Customer::factory()->create(['is_verified' => true]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertInertia(fn ($assert) => $assert->component('Dashboard'));
    }

    public function test_unverified_customers_cannot_access_dashboard()
    {
        $customer = Customer::factory()->create(['is_verified' => false]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/dashboard');

        $response->assertRedirect(); // Redirect based on CheckVerifiedCustomer middleware
    }
}
