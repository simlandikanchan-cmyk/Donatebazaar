<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class PublishBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'publish_at' => [
                'nullable',
                'date',
                'after_or_equal:now',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

        ];
    }
}