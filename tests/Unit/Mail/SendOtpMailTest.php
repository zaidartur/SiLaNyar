<?php

namespace Tests\Unit\Mail;

use App\Mail\SendOtpMail;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SendOtpMailTest extends TestCase
{
    private SendOtpMail $mailNotification;
    private string $otp;
    private string $nama;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otp = '123456';
        $this->nama = 'John Doe';
        $this->mailNotification = new SendOtpMail($this->otp, $this->nama);
    }

    #[Test]
    public function email_memiliki_subject_yang_benar()
    {
        $envelope = $this->mailNotification->envelope();
        
        $this->assertEquals(
            'Send Otp Mail',
            $envelope->subject
        );
    }

    #[Test]
    public function email_menggunakan_view_yang_benar()
    {
        $content = $this->mailNotification->content();
        
        $this->assertEquals(
            'view.name',
            $content->view
        );
    }

    #[Test]
    public function email_tidak_memiliki_attachment()
    {
        $attachments = $this->mailNotification->attachments();
        
        $this->assertEmpty($attachments);
    }

    #[Test]
    public function email_memiliki_data_otp_yang_benar()
    {
        $result = $this->mailNotification->build();
        
        $this->assertEquals(
            'DLH Kabupaten Karanganyar Reset Password',
            $result->subject
        );
        
        $viewData = $result->buildViewData();
        
        $this->assertEquals($this->otp, $viewData['otp']);
        $this->assertEquals($this->nama, $viewData['nama']);
    }
}
