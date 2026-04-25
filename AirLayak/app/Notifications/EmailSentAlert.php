<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmailSentAlert extends Notification
{
    use Queueable;

    public $details;

    // Terima data siapa yang dikirimi email
    public function __construct($details)
    {
        $this->details = $details;
    }

    // Ubah channel menjadi 'database'
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Format data yang akan disimpan di tabel notifications
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Email Peringatan Terkirim',
            'message' => 'Email peringatan overdue telah dikirim ke teknisi untuk area ' . $this->details['location'],
            'technician' => $this->details['name'],
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}