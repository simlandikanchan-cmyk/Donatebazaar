<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KycReminderMail extends Mailable
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
            to: [
                new Address(
                    $this->campaign->user->email,
                    $this->campaign->user->name,
                ),
            ],
            subject: 'Reminder: Upload KYC to Activate Your Campaign — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kyc-reminder',
            with: [
                'campaign' => $this->campaign,
                'user'     => $this->campaign->user,
            ],
        );
    }
}
