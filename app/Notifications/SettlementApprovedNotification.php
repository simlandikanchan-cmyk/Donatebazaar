<?php

namespace App\Notifications;

use App\Models\CampaignSettlement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Settlement Approved — Payout Queued')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your payout request (#'.$this->settlement->id.') has been approved.')
            ->line('Amount: ₹'.number_format($this->settlement->net_amount, 2))
            ->line('The payout has been queued and will be processed shortly. You will receive a confirmation once it is completed.')
            ->action('View Wallet', $url)
            ->salutation('Thanks, '.config('app.name').' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'settlement_approved',
            'settlement_id' => $this->settlement->id,
            'amount' => $this->settlement->net_amount,
            'message' => 'Your settlement #'.$this->settlement->id.' was approved and payout queued for processing.',
            'url' => route('dashboard.wallet'),
        ];
    }
}
