<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ✅ IMPORTANT
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'goal_amount' => str_replace(',', '', $this->goal_amount),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'goal_amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'location' => 'nullable|string|max:255',
            'video_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cover_image' => 'nullable|image|max:2048',
        ];
    }
}