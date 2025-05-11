<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranSukses extends Notification
{
    use Queueable;

    protected $pembayaran;

    /**
     * Create a new notification instance.
     */
    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->view('email.pembayaransukses', [
                'pembayaran' => $this->pembayaran,
                'id_order' => $this->pembayaran->id_order,
                'total_biaya' => $this->pembayaran->total_biaya,
                'tanggal_pembayaran' => $this->pembayaran->updated_at->format('d-m-Y H:i')
            ])
            ->subject('DLH Kabupaten Karanganyar, Pembayaran Telah Sukses');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
