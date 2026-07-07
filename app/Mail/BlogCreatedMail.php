<?php

namespace App\Mail;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BlogCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Blog $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->blog->author->email,
                    $this->blog->author->name,
                ),
            ],
            subject: 'Your Blog Has Been Submitted — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.blog-created',
            with: [
                'blog' => $this->blog,
                'user' => $this->blog->author,
            ],
        );
    }
}
