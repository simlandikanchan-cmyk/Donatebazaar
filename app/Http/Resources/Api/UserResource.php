<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when($this->id === $request->user()?->id, $this->email),
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'role' => $this->role,
            'fundraiser_level' => $this->whenLoaded('assignedLevel', fn () => [
                'name' => $this->assignedLevel?->level_name,
                'max_goal' => $this->assignedLevel?->max_goal_amount,
            ]),
            'is_kyc_approved' => $this->isKycApproved(),
            'phone_verified_at' => $this->phone_verified_at?->toISOString(),
            'last_login_at' => $this->last_login_at?->toISOString(),
            'campaigns_count' => $this->whenCounted('campaigns'),
            'donations_count' => $this->whenCounted('donations'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
