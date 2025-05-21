<?php

namespace Tests\Unit\Mail;

use App\Mail\AduanDiverifikasiNotification;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AduanDiverifikasiNotificationTest extends TestCase
{
    private AduanDiverifikasiNotification $mailNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailNotification = new AduanDiverifikasiNotification();
    }

    #[Test]
    public function email_memiliki_subject_yang_benar()
    {
        $envelope = $this->mailNotification->envelope();
        
        $this->assertEquals(
            'Aduan Diverifikasi Notification',
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
}
