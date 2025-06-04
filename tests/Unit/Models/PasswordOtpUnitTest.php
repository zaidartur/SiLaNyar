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
        $now = now()->startOfSecond();
        Carbon::setTestNow($now);
        
        $otp = PasswordOtp::factory()->create();
        $otp->refresh();
        
        $expectedExpiry = $now->copy()->addMinutes(15);
        $this->assertEquals(
            $expectedExpiry->timestamp,
            $otp->expired_at->timestamp,
            'Expired at harus tepat 15 menit dari created at'
        );
        
        Carbon::setTestNow();
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

    #[Test]
    public function memastikan_otp_bisa_diverifikasi_sebelum_expired()
    {
        $otp = PasswordOtp::factory()->create([
            'expired_at' => Carbon::now()->addMinutes(5)
        ]);
        
        $this->assertFalse($otp->expired_at->isPast());
    }

    #[Test]
    public function memastikan_otp_tidak_bisa_digunakan_setelah_expired()
    {
        // Set waktu sekarang
        $now = now()->startOfSecond();
        Carbon::setTestNow($now);
        
        // Buat OTP yang sudah expired (15 menit yang lalu)
        $otp = PasswordOtp::factory()->create();
        
        // Majukan waktu 16 menit
        Carbon::setTestNow($now->copy()->addMinutes(16));
        
        // Sekarang OTP harusnya expired
        $this->assertTrue(
            $otp->fresh()->expired_at->isPast(),
            'OTP should be expired after 15 minutes'
        );
        
        Carbon::setTestNow(); // Reset
    }

    #[Test]
    public function memastikan_format_email_valid()
    {
        $otp = PasswordOtp::factory()->create([
            'via' => 'email',
            'identitas' => 'test@example.com'
        ]);
        
        $this->assertMatchesRegularExpression(
            '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
            $otp->identitas
        );
    }

    #[Test]
    public function memastikan_format_whatsapp_valid()
    {
        $otp = PasswordOtp::factory()->create([
            'via' => 'whatsapp',
            'identitas' => '+6281234567890'
        ]);
        
        $this->assertMatchesRegularExpression('/^\+62[0-9]{11}$/', $otp->identitas);
    }

    #[Test]
    public function memastikan_otp_numerik()
    {
        $otp = PasswordOtp::factory()->create();
        
        $this->assertIsNumeric($otp->otp);
        $this->assertGreaterThanOrEqual(0, (int)$otp->otp);
        $this->assertLessThanOrEqual(999999, (int)$otp->otp);
    }

    #[Test]
    public function memastikan_created_at_dan_expired_at_selisih_15_menit()
    {
        $now = now()->startOfSecond();
        Carbon::setTestNow($now);
        
        $otp = PasswordOtp::factory()->create();
        $otp->refresh();
        
        // Gunakan timestamp untuk perbandingan yang lebih akurat
        $diffInMinutes = ($otp->expired_at->timestamp - $otp->created_at->timestamp) / 60;
        
        $this->assertEquals(
            15,
            $diffInMinutes,
            'Selisih waktu antara created_at dan expired_at harus tepat 15 menit'
        );
        
        Carbon::setTestNow();
    }

    #[Test]
    public function memastikan_bisa_membuat_multiple_otp_untuk_identitas_sama()
    {
        $identitas = 'test@example.com';
        $via = 'email';
        
        $otpPertama = PasswordOtp::factory()->create([
            'identitas' => $identitas,
            'via' => $via
        ]);
        
        $otpKedua = PasswordOtp::factory()->create([
            'identitas' => $identitas,
            'via' => $via
        ]);
        
        $this->assertNotEquals($otpPertama->otp, $otpKedua->otp);
    }

    #[Test]
    public function memastikan_otp_unique_untuk_waktu_yang_sama()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        
        $otp1 = PasswordOtp::factory()->create();
        $otp2 = PasswordOtp::factory()->create();
        
        $this->assertNotEquals($otp1->otp, $otp2->otp);
        
        Carbon::setTestNow(); // Reset
    }
}
