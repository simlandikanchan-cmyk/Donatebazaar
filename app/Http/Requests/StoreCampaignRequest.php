<?php

namespace App\Http\Requests;

class StoreCampaignRequest extends CampaignFormRequest
{
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'cover_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'products.*.image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'updates.*.document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
    }
}