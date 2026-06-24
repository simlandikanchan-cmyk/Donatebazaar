<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable
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
            to: [
                new Address(
                    $this->donation->donor_email,
                    $this->donation->donor_name ?? 'Donor'
                ),
            ],
            subject: 'Thank You for Your Donation — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-receipt',
            with: [
                'donation'    => $this->donation,
                'campaign'    => $this->donation->campaign,
                'donorName'   => $this->donation->donor_name ?? 'Donor',
                'amount'      => $this->donation->total_amount,
                'platformFee' => $this->donation->platform_fee,
                'netAmount'   => $this->donation->net_amount,
                'receiptNo'   => $this->donation->receipt_number,
                'paidAt'      => $this->donation->paid_at,
            ],
        );
    }
}