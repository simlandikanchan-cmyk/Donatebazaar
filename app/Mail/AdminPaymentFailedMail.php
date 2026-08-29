<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPaymentFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function envelope(): Envelope
    {
        $adminEmail = config('mail.from.address');

        return new Envelope(
            to: [$adminEmail => config('app.name').' Admin'],
            subject: 'Payment Failed — '.$this->donation->order_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-payment-failed',
            with: [
                'donation' => $this->donation,
                'campaign' => $this->donation->campaign,
                'orderId' => $this->donation->order_id,
            ],
        );
    }
}
