<?php

namespace Tests\Unit\Mail;

use App\Mail\KonfirmasiPembayaran;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use stdClass;
use DateTime;

class KonfirmasiPembayaranTest extends TestCase
{
    private KonfirmasiPembayaran $mailNotification;
    private $mockPembayaran;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Membuat mock data pembayaran
        $this->mockPembayaran = new stdClass();
        $this->mockPembayaran->id_order = 'ORDER123';
        $this->mockPembayaran->total_biaya = 150000;
        $this->mockPembayaran->updated_at = new DateTime();
        
        $this->mailNotification = new KonfirmasiPembayaran($this->mockPembayaran);
    }

    #[Test]
    public function email_memiliki_subject_yang_benar()
    {
        $envelope = $this->mailNotification->envelope();
        
        $this->assertEquals(
            'Konfirmasi Pembayaran',
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
    public function email_memiliki_data_pembayaran_yang_benar()
    {
        $result = $this->mailNotification->build();
        
        $viewData = $result->buildViewData();
        
        $this->assertEquals($this->mockPembayaran->id_order, $viewData['id_order']);
        $this->assertEquals($this->mockPembayaran->total_biaya, $viewData['total_biaya']);
        $this->assertEquals(
            $this->mockPembayaran->updated_at->format('d-m-Y H:i'),
            $viewData['tanggal_pembayaran']
        );
    }
}
