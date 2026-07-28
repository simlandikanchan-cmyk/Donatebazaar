<?php

namespace App\Modules\Activity\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => $this->user->name),
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'meta' => $this->meta,
            'time' => $this->created_at->diffForHumans(),
        ];
    }
}
