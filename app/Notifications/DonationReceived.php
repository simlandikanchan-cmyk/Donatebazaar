<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DonationReceived extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database'];
    }

    // 🔥 IMPORTANT: Use toDatabase instead of toArray
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Donation ',
            'message' => 'You received a donation successfully!',
        ];
    }
}