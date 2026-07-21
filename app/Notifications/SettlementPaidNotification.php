<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly User $user
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('dashboard.wallet');

        return (new MailMessage)
            ->subject('Payout Completed')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout (#'.$this->settlement->id.') has been completed.')
            ->line('Amount: ₹'.number_format($this->settlement->net_amount, 2))
            ->line('Reference: '.($this->settlement->gateway_reference ?? 'N/A'))
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_paid',
            'settlement_id' => $this->settlement->id,
            'amount' => $this->settlement->net_amount,
            'gateway_reference' => $this->settlement->gateway_reference,
            'message' => 'Your settlement #'.$this->settlement->id.' was paid out.',
            'url' => route('dashboard.wallet'),
        ];
    }
}
