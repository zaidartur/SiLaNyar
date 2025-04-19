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

    protected function guard()
    {
        return Auth::guard('customer');
    }

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = Customer::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user, 'customer');
        $response->assertRedirect(route('dashboard', absolute: false));
    }


    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = Customer::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        $user = Customer::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user, 'customer');

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest('customer');
    }
}
