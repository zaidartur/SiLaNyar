<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\PasswordOtp;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordOtpUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function memastikan_mass_assignment_protection_berfungsi()
    {
        $otp = new PasswordOtp;
        
        $fillable = [
            'identitas',
            'otp',
            'via',
            'expired_at',
        ];
        
        $this->assertEquals($fillable, $otp->getFillable());
    }

    #[Test]
    public function memastikan_expired_at_berupa_carbon_instance()
    {
        $otp = PasswordOtp::factory()->create();
        
        $this->assertInstanceOf(Carbon::class, $otp->expired_at);
    }

    #[Test]
    public function memastikan_otp_memiliki_panjang_enam_karakter()
    {
        $otp = PasswordOtp::factory()->create();
        
        $this->assertEquals(6, strlen($otp->otp));
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $otp->otp);
    }

    #[Test]
    public function memastikan_via_hanya_bisa_email_atau_whatsapp()
    {
        $otp = PasswordOtp::factory()->create();
        
        $this->assertContains($otp->via, ['email', 'whatsapp']);
    }

    #[Test]
    public function memastikan_identitas_sesuai_dengan_via_email()
    {
        $otp = PasswordOtp::factory()->create([
            'via' => 'email',
            'identitas' => 'test@example.com'
        ]);
        
        $this->assertStringContainsString('@', $otp->identitas);
    }

    #[Test]
    public function memastikan_identitas_sesuai_dengan_via_whatsapp()
    {
        $nomorWhatsapp = '+6281123456789'; // 11 digit
        $otp = PasswordOtp::factory()->create([
            'via' => 'whatsapp',
            'identitas' => $nomorWhatsapp
        ]);
        
        $this->assertStringStartsWith('+62', $otp->identitas);
        $this->assertMatchesRegularExpression('/^\+62[0-9]{11}$/', $otp->identitas);
    }

    #[Test]
    public function memastikan_expired_at_15_menit_dari_created_at()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        
        $otp = PasswordOtp::factory()->create();
        
        $this->assertTrue($otp->expired_at->equalTo($now->copy()->addMinutes(15)));
        
        Carbon::setTestNow(); // Reset untuk test lain
    }

    #[Test]
    public function memastikan_timestamps_berfungsi()
    {
        $otp = PasswordOtp::factory()->create();
        
        $this->assertNotNull($otp->created_at);
        $this->assertNotNull($otp->updated_at);
    }

    #[Test]
    public function memastikan_identitas_tidak_boleh_null()
    {
        $otp = PasswordOtp::factory()->create();
        
        $this->assertNotNull($otp->identitas);
        $this->assertIsString($otp->identitas);
    }
}
