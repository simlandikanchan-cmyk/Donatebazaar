<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly User $user,
        public readonly string $reason
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('dashboard.wallet');

        return (new MailMessage)
            ->subject('Settlement Rejected — Action Required')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout request (#'.$this->settlement->id.') was rejected.')
            ->line('Reason: '.$this->reason)
            ->line('Please review your bank/UPI details and resubmit when ready.')
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_rejected',
            'settlement_id' => $this->settlement->id,
            'amount' => $this->settlement->net_amount,
            'reason' => $this->reason,
            'message' => 'Your settlement #'.$this->settlement->id.' was rejected: '.$this->reason,
            'url' => route('dashboard.wallet'),
        ];
    }
}
