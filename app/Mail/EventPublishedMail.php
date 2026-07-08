<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Event $event,
        public readonly User  $recipient,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Event Published: ' . $this->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-published',
            with: [
                'eventUrl' => $this->event->slug
                    ? route('events.show', $this->event->slug)
                    : (config('app.url') . '/events/' . $this->event->id),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
