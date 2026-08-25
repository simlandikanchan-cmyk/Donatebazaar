<?php

namespace App\Mail;

use App\Models\Donation;
use App\Services\DonationReceiptService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public array $backoff = [60, 300, 900];

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->donation->donor_email,
                    $this->donation->donor_name ?? 'Donor'
                ),
            ],
            subject: 'Thank You for Your Donation — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        $data = app(DonationReceiptService::class)->data($this->donation);

        return new Content(
            view: 'emails.donation-receipt',
            with: $data,
        );
    }
}
