<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignGoalReachedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->campaign->user->email => $this->campaign->user->name],
            subject: 'Congratulations! Your Campaign Goal Has Been Reached — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.campaign-goal-reached',
            with: [
                'campaign' => $this->campaign,
                'user' => $this->campaign->user,
            ],
        );
    }
}
