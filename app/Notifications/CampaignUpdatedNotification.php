<?php

namespace App\Notifications;

use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CampaignUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Campaign $campaign,
        public readonly CampaignUpdate $update,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->preferNotification('campaign_updated', 'email')) {
            $channels[] = 'mail';
        }
        if ($notifiable->preferNotification('campaign_updated', 'database')) {
            $channels[] = 'database';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update from "'.$this->campaign->title.'"')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('There\'s a new update from the campaign **"'.$this->campaign->title.'"** that you supported.')
            ->line('**'.$this->update->title.'**')
            ->when($this->update->content, fn ($mail) => $mail->line(Str::limit(strip_tags($this->update->content), 200)))
            ->action('View Update', route('campaign.show', $this->campaign))
            ->salutation('Thanks, '.config('app.name'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'campaign_updated',
            'campaign_id' => $this->campaign->id,
            'campaign' => $this->campaign->title,
            'update_id' => $this->update->id,
            'update_title' => $this->update->title,
            'message' => 'New update from "'.$this->campaign->title.'": '.$this->update->title,
            'url' => route('campaign.show', $this->campaign),
        ];
    }
}
