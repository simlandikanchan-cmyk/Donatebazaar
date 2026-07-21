<?php

namespace App\Mail;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BlogStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Blog $blog;

    public string $status;

    public ?string $reason;

    public function __construct(Blog $blog, string $status, ?string $reason = null)
    {
        $this->blog = $blog;
        $this->status = $status;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        $subject = $this->status === 'published'
            ? 'Your Blog Has Been Approved — '.config('app.name')
            : 'Your Blog Status Update — '.config('app.name');

        return new Envelope(
            to: [
                new Address(
                    $this->blog->author->email,
                    $this->blog->author->name,
                ),
            ],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.blog-status',
            with: [
                'blog' => $this->blog,
                'user' => $this->blog->author,
                'status' => $this->status,
                'reason' => $this->reason,
            ],
        );
    }
}
