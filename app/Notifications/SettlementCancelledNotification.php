<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly array $data) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('settlement_cancelled', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('settlement_cancelled', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('dashboard.wallet');

        return (new MailMessage)
            ->subject('Settlement Cancelled')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout request (#'.($this->data['settlement_id'] ?? 'N/A').') has been cancelled.')
            ->line('Amount: ₹'.number_format($this->data['amount'] ?? 0, 2))
            ->when(isset($this->data['reason']), function (MailMessage $mail) {
                return $mail->line('Reason: '.$this->data['reason']);
            })
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_cancelled',
            'settlement_id' => $this->data['settlement_id'] ?? null,
            'amount' => $this->data['amount'] ?? 0,
            'reason' => $this->data['reason'] ?? null,
            'message' => 'Settlement #'.($this->data['settlement_id'] ?? 'N/A').' has been cancelled.',
            'url' => route('dashboard.wallet'),
        ];
    }
}
