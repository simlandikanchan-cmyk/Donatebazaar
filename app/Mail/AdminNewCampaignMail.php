<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewCampaignMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function envelope(): Envelope
    {
        $adminEmail = config('mail.from.address');

        return new Envelope(
            to: [$adminEmail => config('app.name').' Admin'],
            subject: 'New Campaign Submitted for Review — '.$this->campaign->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-campaign',
            with: [
                'campaign' => $this->campaign,
                'user' => $this->campaign->user,
            ],
        );
    }
}
