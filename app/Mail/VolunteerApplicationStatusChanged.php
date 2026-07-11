<?php

namespace App\Mail;

use App\Models\VolunteerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerApplicationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public VolunteerApplication $application;

    public function __construct(VolunteerApplication $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Volunteer Application ' . ucfirst($this->application->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.volunteer.application-status',
            with: ['application' => $this->application],
        );
    }
}
