<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

abstract class EventFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function baseRules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'event_date' => ['required', 'date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable', 'after:start_time'],
            'goal_amount' => ['nullable', 'numeric', 'min:0'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }
}