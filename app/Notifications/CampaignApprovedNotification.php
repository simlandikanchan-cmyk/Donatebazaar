<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CampaignApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Campaign $campaign,
        public readonly ?string $adminNote = null,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('campaign_approved', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('campaign_approved', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Campaign Approved!')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your campaign **"'.$this->campaign->title.'"** has been approved and is now live!')
            ->when($this->adminNote, fn ($mail) => $mail->line('**Note from admin:** '.$this->adminNote))
            ->action('View Campaign', route('campaign.show', $this->campaign))
            ->line('Start sharing your campaign with supporters to raise funds.')
            ->salutation('Thanks, '.config('app.name').' Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'campaign_approved',
            'campaign_id' => $this->campaign->id,
            'campaign' => $this->campaign->title,
            'message' => 'Your campaign "'.$this->campaign->title.'" has been approved and is now live!',
            'url' => route('campaign.show', $this->campaign),
        ];
    }
}

