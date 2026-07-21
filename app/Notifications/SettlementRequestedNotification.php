<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly array $data) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('dashboard.wallet');

        return (new MailMessage)
            ->subject('Settlement Requested')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('A payout request has been submitted.')
            ->line('Settlement ID: '.($this->data['settlement_id'] ?? 'N/A'))
            ->line('Amount: ₹'.number_format($this->data['amount'] ?? 0, 2))
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_requested',
            'settlement_id' => $this->data['settlement_id'] ?? null,
            'amount' => $this->data['amount'] ?? 0,
            'message' => 'Settlement #'.($this->data['settlement_id'] ?? 'N/A').' has been requested.',
            'url' => route('dashboard.wallet'),
        ];
    }
}
