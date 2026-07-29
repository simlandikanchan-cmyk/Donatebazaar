<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly User $user,
        public readonly string $reason
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('settlement_failed', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('settlement_failed', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('dashboard.wallet');

        return (new MailMessage)
            ->subject('Payout Failed — Action Required')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout request (#'.$this->settlement->id.') could not be completed.')
            ->line('Amount: ₹'.number_format($this->settlement->net_amount, 2))
            ->line('Reason: '.$this->reason)
            ->line('The funds have been returned to your wallet balance. Please review your payout details and contact support if needed.')
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_failed',
            'settlement_id' => $this->settlement->id,
            'amount' => $this->settlement->net_amount,
            'reason' => $this->reason,
            'message' => 'Your settlement #'.$this->settlement->id.' payout failed: '.$this->reason.'. Funds returned to wallet.',
            'url' => route('dashboard.wallet'),
        ];
    }
}
