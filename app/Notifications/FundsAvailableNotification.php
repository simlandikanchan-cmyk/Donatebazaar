<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FundsAvailableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly float $amount,
        public readonly int $donationCount,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Funds Released — Ready for Payout')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('₹'.number_format($this->amount, 2).' from '.$this->donationCount.' donation(s) has been released from the hold period.')
            ->line('The funds are now available in your wallet balance. You can request a payout anytime.')
            ->action('View Wallet', route('dashboard.wallet'))
            ->salutation('Thanks, '.config('app.name'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'funds_available',
            'amount' => $this->amount,
            'donation_count' => $this->donationCount,
            'message' => '₹'.number_format($this->amount, 2).' from '.$this->donationCount.' donation(s) is now available for payout.',
            'url' => route('dashboard.wallet'),
        ];
    }
}
