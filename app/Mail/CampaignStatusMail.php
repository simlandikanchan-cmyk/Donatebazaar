<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Campaign $campaign;
    public string $status;
    public ?string $reason;

    public function __construct(Campaign $campaign, string $status, ?string $reason = null)
    {
        $this->campaign = $campaign;
        $this->status   = $status;
        $this->reason   = $reason;
    }

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? 'Your Campaign Has Been Approved — ' . config('app.name')
            : 'Your Campaign Status Update — ' . config('app.name');

        return new Envelope(
            to: [
                new Address(
                    $this->campaign->user->email,
                    $this->campaign->user->name,
                ),
            ],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.campaign-status',
            with: [
                'campaign' => $this->campaign,
                'user'     => $this->campaign->user,
                'status'   => $this->status,
                'reason'   => $this->reason,
            ],
        );
    }
}
