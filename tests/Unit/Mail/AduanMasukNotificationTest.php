<?php

namespace Tests\Unit\Mail;

use App\Mail\AduanMasukNotification;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AduanMasukNotificationTest extends TestCase
{
    private AduanMasukNotification $mailNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailNotification = new AduanMasukNotification();
    }

    #[Test]
    public function email_memiliki_subject_yang_benar()
    {
        $envelope = $this->mailNotification->envelope();
        
        $this->assertEquals(
            'Aduan Masuk Notification',
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
    public function email_menggunakan_nama_dari_class()
    {
        // Verifikasi bahwa nama class digunakan sebagai identifier
        $className = get_class($this->mailNotification);
        $this->assertEquals('App\Mail\AduanMasukNotification', $className);
    }
}
