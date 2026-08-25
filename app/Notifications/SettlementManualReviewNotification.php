<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementManualReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly array $data) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('settlement_manual_review', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('settlement_manual_review', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('dashboard.wallet');

        return (new MailMessage)
            ->subject('Settlement Requires Manual Review')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout request (#'.($this->data['settlement_id'] ?? 'N/A').') requires manual review.')
            ->line('Amount: ₹'.number_format($this->data['amount'] ?? 0, 2))
            ->line('Risk Score: '.($this->data['risk_score'] ?? 'N/A'))
            ->line('Our team will review and notify you once it is approved.')
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_manual_review',
            'settlement_id' => $this->data['settlement_id'] ?? null,
            'amount' => $this->data['amount'] ?? 0,
            'risk_score' => $this->data['risk_score'] ?? null,
            'message' => 'Settlement #'.($this->data['settlement_id'] ?? 'N/A').' is pending manual review.',
            'url' => route('dashboard.wallet'),
        ];
    }
}

