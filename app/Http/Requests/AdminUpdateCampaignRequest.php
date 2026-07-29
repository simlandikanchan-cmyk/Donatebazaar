<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1',
            'status' => 'nullable|string',
            'end_date' => 'nullable|date',
        ];
    }
}
