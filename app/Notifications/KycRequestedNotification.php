<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Campaign $campaign,
        public readonly string $adminMessage = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('kyc.upload.form', ['campaign' => $this->campaign->id]);

        return (new MailMessage)
            ->subject('Action Required: Upload KYC to Activate Your Campaign')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your campaign **"' . $this->campaign->title . '"** is pending approval.')
            ->line('Before we can approve it, we need you to verify your identity by submitting KYC documents.')
            ->when($this->adminMessage, fn ($mail) => $mail->line('**Note from admin:** ' . $this->adminMessage))
            ->action('Upload KYC Documents', $url)
            ->line('Once your KYC is approved, your campaign will be reviewed for activation.')
            ->salutation('Thanks, ' . config('app.name') . ' Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'kyc_requested',
            'campaign_id' => $this->campaign->id,
            'campaign'    => $this->campaign->title,
            'message'     => $this->adminMessage ?: 'Please upload your KYC documents to proceed with campaign approval.',
            'url'         => route('kyc.upload.form', ['campaign' => $this->campaign->id]),
        ];
    }
}