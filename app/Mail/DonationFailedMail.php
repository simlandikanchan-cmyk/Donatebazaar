<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->donation->donor_email ? [$this->donation->donor_email => $this->donation->donor_name ?? 'Donor'] : null,
            subject: 'Payment Not Completed — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-failed',
            with: [
                'donation' => $this->donation,
                'campaign' => $this->donation->campaign,
                'donorName' => $this->donation->donor_name ?? 'Donor',
                'amount' => $this->donation->total_amount,
            ],
        );
    }
}
