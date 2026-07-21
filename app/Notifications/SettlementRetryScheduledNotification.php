<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementRetryScheduledNotification extends Notification implements ShouldQueue
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
            ->subject('Payout Retry Scheduled')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout (#'.($this->data['settlement_id'] ?? 'N/A').') failed and a retry has been scheduled.')
            ->line('Amount: ₹'.number_format($this->data['amount'] ?? 0, 2))
            ->line('Next retry at: '.($this->data['next_retry_at'] ?? 'N/A'))
            ->line('Attempt: '.($this->data['retry_count'] ?? 'N/A'))
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_retry_scheduled',
            'settlement_id' => $this->data['settlement_id'] ?? null,
            'amount' => $this->data['amount'] ?? 0,
            'next_retry_at' => $this->data['next_retry_at'] ?? null,
            'retry_count' => $this->data['retry_count'] ?? null,
            'message' => 'Settlement #'.($this->data['settlement_id'] ?? 'N/A').' payout retry scheduled.',
            'url' => route('dashboard.wallet'),
        ];
    }
}
