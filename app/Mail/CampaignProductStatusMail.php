<?php

namespace App\Mail;

use App\Models\CampaignProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignProductStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public CampaignProduct $product;
    public string $status;
    public ?string $reason;
    public ?\Illuminate\Contracts\Auth\Authenticatable $admin;

    public function __construct(CampaignProduct $product, string $status, ?string $reason = null, ?\Illuminate\Contracts\Auth\Authenticatable $admin = null)
    {
        $this->product = $product;
        $this->status  = $status;
        $this->reason  = $reason;
        $this->admin   = $admin ?? auth()->user();
    }

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? 'Your Product Has Been Approved — ' . config('app.name')
            : 'Your Product Has Been Rejected — ' . config('app.name');

        return new Envelope(
            to: [
                new Address(
                    $this->product->user->email,
                    $this->product->user->name,
                ),
            ],
            replyTo: [
                new Address(
                    $this->admin?->email ?? config('mail.from.address'),
                    $this->admin?->name ?? config('mail.from.name'),
                ),
            ],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.campaign-product-status',
            with: [
                'product' => $this->product,
                'user'    => $this->product->user,
                'status'  => $this->status,
                'reason'  => $this->reason,
                'admin'   => $this->admin,
            ],
        );
    }
}
