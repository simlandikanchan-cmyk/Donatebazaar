<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:20000',
            'goal_amount' => 'required|numeric|min:1|max:500000',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'video_url' => 'nullable|url',
            'products.*.image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'updates.*.document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'goal_amount' => str_replace(',', '', $this->goal_amount),
            'title' => strip_tags($this->title),
        ]);
    }
}
