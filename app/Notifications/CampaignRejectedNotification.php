<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CampaignRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Campaign $campaign,
        public readonly string $reason,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('campaign_rejected', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('campaign_rejected', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Campaign Update — Needs Attention')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your campaign **"'.$this->campaign->title.'"** has been reviewed and was not approved at this time.')
            ->line('**Reason:** '.$this->reason)
            ->line('You can update your campaign and resubmit it for review.')
            ->action('Edit Campaign', route('campaign.edit', $this->campaign))
            ->salutation('Thanks, '.config('app.name').' Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'campaign_rejected',
            'campaign_id' => $this->campaign->id,
            'campaign' => $this->campaign->title,
            'reason' => $this->reason,
            'message' => 'Your campaign "'.$this->campaign->title.'" was not approved: '.$this->reason,
            'url' => route('campaign.edit', $this->campaign),
        ];
    }
}
