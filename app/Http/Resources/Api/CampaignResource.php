<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'video_url' => $this->video_url,
            'goal_amount' => $this->goal_amount,
            'raised_amount' => $this->whenLoaded('donations', fn () => $this->donations()->sum('amount')),
            'location' => $this->location,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'campaign_state' => $this->campaign_state,
            'is_featured' => (bool) $this->is_featured,
            'is_urgent' => (bool) $this->is_urgent,
            'is_followed' => $this->when(auth()->check(), fn () => $this->isFollowedBy(auth()->user())),
            'followers_count' => $this->whenCounted('followers'),
            'donations_count' => $this->whenCounted('donations'),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ]),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'avatar' => $this->user?->avatar,
            ]),
            'products' => $this->whenLoaded('products', fn () => $this->products->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'stock' => $p->stock,
            ])),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
