<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency ?? 'INR',
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'is_anonymous' => (bool) $this->is_anonymous,
            'message' => $this->message,
            'campaign' => $this->whenLoaded('campaign', fn () => [
                'id' => $this->campaign?->id,
                'title' => $this->campaign?->title,
                'slug' => $this->campaign?->slug,
            ]),
            'donor' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->is_anonymous ? 'Anonymous' : $this->user?->name,
            ]),
            'receipt_url' => $this->when(! $this->is_anonymous, fn () => route('donations.receipt', $this->id)),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
