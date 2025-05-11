<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\PasswordOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class ResetPasswordCustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'kontak_pribadi' => '+6281234567890',
            'password' => Hash::make('password123')
        ]);
    }

    // public function test_customer_bisa_melihat_form_lupa_password()
    // {
    //     $response = $this->get(route('customer.password.lupa'));

    //     $response->assertStatus(200)
    //         ->assertInertia(fn ($assert) => $assert
    //             ->component('customer/LupaPassword')
    //         );
    // }

    public function test_customer_bisa_meminta_otp_via_email()
    {
        Mail::fake();

        $response = $this->post('/lupapassword', [
            'identitas' => 'test@example.com',
            'via' => 'email'
        ]);

        $response->assertRedirect(route('customer.password.reset'));
        
        Mail::assertSent(SendOtpMail::class, function ($mail) {
            return $mail->hasTo('test@example.com') && 
                   isset($mail->otp) && 
                   isset($mail->nama);
        });

        $this->assertDatabaseHas('password_otp', [
            'identitas' => 'test@example.com',
            'via' => 'email'
        ]);
    }

    public function test_validasi_identitas_harus_ada()
    {
        $response = $this->post(route('customer.password.lupa'), [
            'via' => 'email'
        ]);

        $response->assertSessionHasErrors('identitas');
    }

    // public function test_customer_bisa_verifikasi_otp()
    // {
    //     $otp = '123456';
        
    //     PasswordOtp::factory()->create([
    //         'identitas' => 'test@example.com',
    //         'otp' => $otp,
    //         'expired_at' => now()->addMinutes(5)
    //     ]);

    //     $response = $this->post('/verifikasiotp', [
    //         'identitas' => 'test@example.com',
    //         'otp' => $otp
    //     ]);

    //     $response->assertInertia(fn ($assert) => $assert
    //         ->component('customer/GantiPassword')
    //     );
    // }

    public function test_otp_tidak_valid_akan_ditolak()
    {
        $response = $this->post('/verifikasiotp', [
            'identitas' => 'test@example.com',
            'otp' => '000000'
        ]);

        $response->assertSessionHasErrors('otp');
    }

    public function test_customer_bisa_ganti_password_setelah_verifikasi()
    {
        $response = $this->post('/gantipassword', [
            'identitas' => 'test@example.com',
            'password' => 'password_baru123',
            'password_confirmation' => 'password_baru123'
        ]);

        $response->assertRedirect(route('customer.login'));
        
        $this->customer->refresh();
        $this->assertTrue(Hash::check('password_baru123', $this->customer->password));
    }

    public function test_password_baru_harus_dikonfirmasi()
    {
        $response = $this->post('/gantipassword', [
            'identitas' => 'test@example.com',
            'password' => 'password_baru123',
            'password_confirmation' => 'password_berbeda'
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_otp_kadaluarsa_tidak_bisa_digunakan()
    {
        PasswordOtp::factory()->create([
            'identitas' => 'test@example.com',
            'otp' => '123456',
            'expired_at' => now()->subMinutes(6)
        ]);

        $response = $this->post('/verifikasiotp', [
            'identitas' => 'test@example.com',
            'otp' => '123456'
        ]);

        $response->assertSessionHasErrors('otp');
    }
}
