<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HasilUjiNotification extends Notification
{
    use Queueable;

    protected $HasilUji;

    /**
     * Create a new notification instance.
     */
    public function __construct($HasilUji)
    {
        $this->HasilUji = $HasilUji;
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
            ->view('email.hasiluji', [
                'hasil_uji' => $this->HasilUji
            ])
            ->subject('DLH Kabupaten Karanganyar Hasil Uji Anda Telah Tersedia');
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
