<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettlementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency ?? 'INR',
            'status' => $this->status,
            'gateway_reference' => $this->gateway_reference,
            'failure_reason' => $this->failure_reason,
            'processed_at' => $this->processed_at?->toISOString(),
            'campaign' => $this->whenLoaded('campaign', fn () => [
                'id' => $this->campaign?->id,
                'title' => $this->campaign?->title,
            ]),
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'donation_id' => $item->donation_id,
                'amount' => $item->amount,
            ])),
            'state_history' => $this->whenLoaded('stateLogs', fn () => $this->stateLogs->map(fn ($log) => [
                'from' => $log->from_status,
                'to' => $log->to_status,
                'at' => $log->created_at?->toISOString(),
            ])),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
