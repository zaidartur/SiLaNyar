<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KonfirmasiPembayaran extends Mailable
{
    use Queueable, SerializesModels;

    public $pembayaran;
    /**
     * Create a new message instance.
     */
    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function build()
    {
        return $this->view('email.konfirmasipembayaran')
                    ->with([
                        'pembayaran' => $this->pembayaran,
                        'id_order' => $this->pembayaran->id_order,
                        'total_biaya' => $this->pembayaran->total_biaya,
                        'tanggal_pembayaran' => $this->pembayaran->updated_at->format('d-m-Y H:i')
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembayaran',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
