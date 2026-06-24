<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'               => ['required', 'string', 'max:255'],
            'slug'                => ['nullable', 'string', 'max:255'],
            'excerpt'             => ['nullable', 'string', 'max:500'],
            'content'             => ['required', 'string'],
            'cover_image'         => ['nullable', 'image', 'max:5120'],
            'category_id'         => ['nullable', 'integer', 'exists:categories,id'],
            'tag_ids'             => ['nullable', 'array'],
            'tag_ids.*'           => ['integer', 'exists:tags,id'],
            'tags'                => ['nullable', 'string'],
            'status'              => ['nullable', 'string', 'in:draft,pending,approved,rejected,archived,flagged'],
            'is_featured'         => ['nullable', 'boolean'],
            'allow_comments'      => ['nullable', 'boolean'],
            'meta_title'          => ['nullable', 'string', 'max:100'],
            'meta_description'    => ['nullable', 'string', 'max:160'],
            'read_time_minutes'   => ['nullable', 'integer', 'min:1', 'max:60'],
            'canonical_url'       => ['nullable', 'url'],
            'og_title'            => ['nullable', 'string', 'max:100'],
            'og_description'      => ['nullable', 'string', 'max:300'],
            'og_image'            => ['nullable', 'image', 'max:5120'],
            'language'            => ['nullable', 'string', 'max:10'],
            'content_type'        => ['nullable', 'string'],
            'reading_level'       => ['nullable', 'string'],
            'author_override'     => ['nullable', 'string', 'max:100'],
            'author_role_override'=> ['nullable', 'string', 'max:100'],
            'linked_campaign_id'  => ['nullable', 'integer', 'exists:campaigns,id'],
            'publish_now'         => ['nullable', 'boolean'],
            'schedule_toggle'     => ['nullable', 'boolean'],
            'scheduled_at_date'   => ['nullable', 'date'],
            'scheduled_at_time'   => ['nullable', 'string'],
            'is_pinned'           => ['nullable', 'boolean'],
            'allow_likes'         => ['nullable', 'boolean'],
            'show_share'          => ['nullable', 'boolean'],
            'syndicate_newsletter'=> ['nullable', 'boolean'],
            'remove_cover'        => ['nullable', 'string'],
        ];
    }
}