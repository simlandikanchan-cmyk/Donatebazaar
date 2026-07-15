<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationRefundMail extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;
    public Refund $refund;

    public function __construct(Donation $donation, Refund $refund)
    {
        $this->donation = $donation;
        $this->refund = $refund;
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
            subject: 'Refund Processed — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-refund',
            with: [
                'donation'  => $this->donation,
                'refund'    => $this->refund,
                'campaign'  => $this->donation->campaign,
                'donorName' => $this->donation->donor_name ?? 'Donor',
                'amount'    => $this->refund->amount,
                'reason'    => $this->refund->reason,
                'processedAt' => $this->refund->processed_at,
                'refundId'  => $this->refund->id,
            ],
        );
    }
}
