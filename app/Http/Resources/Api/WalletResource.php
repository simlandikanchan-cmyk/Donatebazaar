<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'balance' => $this->balance,
            'reserved_balance' => $this->reserved_balance,
            'available_balance' => $this->balance - $this->reserved_balance,
            'currency' => $this->currency ?? 'INR',
            'total_credits' => $this->whenLoaded('transactions', fn () => $this->transactions()->where('type', 'credit')->sum('amount')),
            'total_debits' => $this->whenLoaded('transactions', fn () => $this->transactions()->where('type', 'debit')->sum('amount')),
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner?->id,
                'name' => $this->owner?->name ?? $this->owner?->title,
                'type' => $this->owner?->getMorphClass(),
            ]),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
