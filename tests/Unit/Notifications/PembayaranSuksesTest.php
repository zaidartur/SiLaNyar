<?php

namespace Tests\Unit\Notifications;

use App\Notifications\PembayaranSukses;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Notifications\Messages\MailMessage;
use stdClass;
use DateTime;

class PembayaranSuksesTest extends TestCase
{
    private PembayaranSukses $notification;
    private $mockPembayaran;
    private $mockNotifiable;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock data pembayaran
        $this->mockPembayaran = new stdClass();
        $this->mockPembayaran->id_order = 'ORDER123';
        $this->mockPembayaran->total_biaya = 150000;
        $this->mockPembayaran->updated_at = new DateTime();
        
        // Mock notifiable object
        $this->mockNotifiable = new stdClass();
        $this->mockNotifiable->email = 'pelanggan@example.com';
        
        $this->notification = new PembayaranSukses($this->mockPembayaran);
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
            'DLH Kabupaten Karanganyar, Pembayaran Telah Sukses',
            $mailMessage->subject
        );
    }

    #[Test]
    public function email_menggunakan_view_yang_benar()
    {
        $mailMessage = $this->notification->toMail($this->mockNotifiable);
        
        $this->assertEquals('email.pembayaransukses', $mailMessage->view);
    }

    #[Test]
    public function email_memiliki_data_pembayaran_yang_benar()
    {
        $mailMessage = $this->notification->toMail($this->mockNotifiable);
        
        $viewData = $mailMessage->viewData;
        
        $this->assertEquals($this->mockPembayaran, $viewData['pembayaran']);
        $this->assertEquals($this->mockPembayaran->id_order, $viewData['id_order']);
        $this->assertEquals($this->mockPembayaran->total_biaya, $viewData['total_biaya']);
        $this->assertEquals(
            $this->mockPembayaran->updated_at->format('d-m-Y H:i'),
            $viewData['tanggal_pembayaran']
        );
    }

    #[Test]
    public function to_array_mengembalikan_array_kosong()
    {
        $array = $this->notification->toArray($this->mockNotifiable);
        
        $this->assertEmpty($array);
    }
}
