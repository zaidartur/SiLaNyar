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
    public function email_menggunakan_view_yang_benar_pada_method_content()
    {
        $content = $this->mailNotification->content();
        
        $this->assertEquals(
            'view.name',
            $content->view
        );
    }
    
    #[Test]
    public function email_menggunakan_view_yang_benar_pada_method_build()
    {
        $result = $this->mailNotification->build();
        
        // Periksa view yang digunakan pada method build
        // Karena $result->view adalah string, kita akses langsung tanpa indeks array
        $this->assertEquals(
            'email.konfirmasipembayaran',
            $result->view
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
        
        $viewData = $result->viewData;
        
        $this->assertEquals($this->mockPembayaran->id_order, $viewData['id_order']);
        $this->assertEquals($this->mockPembayaran->total_biaya, $viewData['total_biaya']);
        $this->assertEquals(
            $this->mockPembayaran->updated_at->format('d-m-Y H:i'),
            $viewData['tanggal_pembayaran']
        );
        $this->assertSame($this->mockPembayaran, $viewData['pembayaran']);
    }
    
    #[Test]
    public function email_menggunakan_nama_dari_class()
    {
        // Verifikasi bahwa nama class digunakan sebagai identifier
        $className = get_class($this->mailNotification);
        $this->assertEquals('App\Mail\KonfirmasiPembayaran', $className);
    }
}
