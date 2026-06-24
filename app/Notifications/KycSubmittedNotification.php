<?php

namespace App\Notifications;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly User $user,
        public readonly Campaign $campaign
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.campaign.show', $this->campaign->id);

        return (new MailMessage)
            ->subject('KYC Submitted — Campaign Ready for Review')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('User **' . $this->user->name . '** (' . $this->user->email . ') has submitted their KYC documents.')
            ->line('Campaign: **"' . $this->campaign->title . '"** is now ready for your review.')
            ->action('Review Campaign', $url)
            ->line('Please verify the KYC documents and approve or reject the campaign.')
            ->salutation('Thanks, ' . config('app.name') . ' System');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'kyc_submitted',
            'campaign_id' => $this->campaign->id,
            'campaign'    => $this->campaign->title,
            'user_id'     => $this->user->id,
            'user'        => $this->user->name,
            'message'     => $this->user->name . ' has submitted KYC for campaign "' . $this->campaign->title . '".',
            'url'         => route('admin.campaign.show', $this->campaign->id),
        ];
    }
}