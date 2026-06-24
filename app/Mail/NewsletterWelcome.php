<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public string $subscriberEmail;

    public function __construct(string $email)
    {
        $this->subscriberEmail = $email;
    }

    public function build()
    {
        return $this->subject('Welcome to our Newsletter!')
                    ->view('emails.newsletter-welcome');
    }
}