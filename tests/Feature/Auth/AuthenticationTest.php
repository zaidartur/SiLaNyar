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
        $response->assertInertia(fn ($assert) => $assert->component('auth/Login'));
    }

    public function test_customers_can_authenticate_using_the_login_screen()
    {
        $customer = Customer::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect('/dashboard');
    }

    public function test_customers_can_not_authenticate_with_invalid_password()
    {
        $customer = Customer::factory()->create([
            'password' => bcrypt('password')
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
            'password' => bcrypt('password')
        ]);

        $this->actingAs($customer, 'customer');

        $this->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest('customer');
    }

    public function test_verified_customers_can_access_dashboard()
    {
        // Since is_verified doesn't exist, we'll use a different approach
        // You might want to adjust this based on how verification is implemented
        $customer = Customer::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertInertia(fn ($assert) => $assert->component('Dashboard'));
    }

    public function test_unverified_customers_cannot_access_dashboard()
    {
        // Assuming the verification is based on email_verified_at
        $customer = Customer::factory()->create([
            'password' => bcrypt('password'),
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/dashboard');

        // This should redirect based on your CheckVerifiedCustomer middleware
        $response->assertRedirect();
    }
}
