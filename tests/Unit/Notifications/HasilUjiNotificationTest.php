<?php

namespace Tests\Unit\Notifications;

use App\Notifications\HasilUjiNotification;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Notifications\Messages\MailMessage;
use stdClass;

class HasilUjiNotificationTest extends TestCase
{
    private HasilUjiNotification $notification;
    private $mockHasilUji;
    private $mockNotifiable;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock data hasil uji
        $this->mockHasilUji = new stdClass();
        $this->mockHasilUji->id = 1;
        $this->mockHasilUji->nomor_uji = 'UJI-001';
        $this->mockHasilUji->tanggal_uji = '2024-01-20';
        
        // Mock notifiable object
        $this->mockNotifiable = new stdClass();
        $this->mockNotifiable->email = 'test@example.com';
        
        $this->notification = new HasilUjiNotification($this->mockHasilUji);
    }

    #[Test]
    public function notifikasi_dikirim_melalui_channel_email()
    {
        $channels = $this->notification->via($this->mockNotifiable);
        
        $this->assertEquals(['mail'], $channels);
    }

    #[Test]
    public function email_memiliki_subject_yang_benar()
    {
        $mailMessage = $this->notification->toMail($this->mockNotifiable);
        
        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        $this->assertEquals(
            'DLH Kabupaten Karanganyar Hasil Uji Anda Telah Tersedia',
            $mailMessage->subject
        );
    }

    #[Test]
    public function email_menggunakan_view_yang_benar()
    {
        $mailMessage = $this->notification->toMail($this->mockNotifiable);
        
        $this->assertEquals('email.hasiluji', $mailMessage->view);
    }

    #[Test]
    public function email_memiliki_data_hasil_uji_yang_benar()
    {
        $mailMessage = $this->notification->toMail($this->mockNotifiable);
        
        $viewData = $mailMessage->viewData;
        
        $this->assertArrayHasKey('hasil_uji', $viewData);
        $this->assertEquals($this->mockHasilUji, $viewData['hasil_uji']);
    }

    #[Test]
    public function to_array_mengembalikan_array_kosong()
    {
        $array = $this->notification->toArray($this->mockNotifiable);
        
        $this->assertEmpty($array);
    }
    
    #[Test]
    public function konstruktor_menetapkan_properti_hasil_uji()
    {
        $reflection = new \ReflectionClass($this->notification);
        $property = $reflection->getProperty('HasilUji');
        $property->setAccessible(true);
        
        $this->assertEquals($this->mockHasilUji, $property->getValue($this->notification));
    }

    #[Test]
    public function trait_queueable_digunakan()
    {
        $this->assertContains(
            'Illuminate\Bus\Queueable',
            array_map(
                function ($trait) {
                    return $trait->getName();
                },
                (new \ReflectionClass($this->notification))->getTraits()
            )
        );
    }
}
