<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

abstract class BlogFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function baseRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:tags,id'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'is_featured' => ['nullable', 'boolean'],
            'allow_comments' => ['nullable', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
            'allow_likes' => ['nullable', 'boolean'],
            'show_share' => ['nullable', 'boolean'],
            'read_time_minutes' => ['nullable', 'integer', 'min:1', 'max:60'],
            'canonical_url' => ['nullable', 'url'],
            'og_title' => ['nullable', 'string', 'max:100'],
            'og_description' => ['nullable', 'string', 'max:300'],
            'og_image' => ['nullable', 'image', 'max:5120'],
            'language' => ['nullable', 'string', 'max:10'],
            'content_type' => ['nullable', 'string'],
            'reading_level' => ['nullable', 'string'],
            'linked_campaign_id' => ['nullable', 'integer', 'exists:campaigns,id'],
            'remove_cover' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
        ];
    }
}
