<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementProcessingStartedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly array $data) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('settlement_processing_started', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('settlement_processing_started', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('dashboard.wallet');

        return (new MailMessage)
            ->subject('Payout Processing Started')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout (#'.($this->data['settlement_id'] ?? 'N/A').') is now being processed.')
            ->line('Amount: ₹'.number_format($this->data['amount'] ?? 0, 2))
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_processing_started',
            'settlement_id' => $this->data['settlement_id'] ?? null,
            'amount' => $this->data['amount'] ?? 0,
            'message' => 'Settlement #'.($this->data['settlement_id'] ?? 'N/A').' payout processing started.',
            'url' => route('dashboard.wallet'),
        ];
    }
}

