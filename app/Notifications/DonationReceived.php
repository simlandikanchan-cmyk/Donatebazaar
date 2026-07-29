<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly float $amount,
        public readonly string $donorName,
        public readonly string $campaignTitle,
        public readonly int $campaignId,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('donation_received', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('donation_received', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Donation Received!')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('You received a donation of ₹'.number_format($this->amount, 2))
            ->when($this->donorName !== 'Anonymous', fn ($m) => $m->line('From: '.$this->donorName))
            ->line('Campaign: '.$this->campaignTitle)
            ->action('View Campaign', route('campaign.show', $this->campaignId))
            ->salutation('Thanks, '.config('app.name'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'donation_received',
            'amount' => $this->amount,
            'donor_name' => $this->donorName,
            'campaign_id' => $this->campaignId,
            'campaign' => $this->campaignTitle,
            'message' => 'You received a donation of ₹'.number_format($this->amount, 2).' for "'.$this->campaignTitle.'".',
            'url' => route('campaign.show', $this->campaignId),
        ];
    }
}
