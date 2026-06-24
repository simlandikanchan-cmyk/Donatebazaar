<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $blogId = $this->route('blog')?->id;

        return [

            'title' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('blogs', 'slug')
                    ->ignore($blogId),
            ],

            'excerpt' => [
                'nullable',
                'string',
                'max:500',
            ],

            'content' => [
                'required',
                'string',
                'min:50',
            ],

            'cover_image' => [
                'nullable',
                'sometimes',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],

            'category_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],

            'tag_ids' => [
                'nullable',
                'array',
            ],

            'tag_ids.*' => [
                'integer',
                'exists:tags,id',
            ],

            'meta_title' => [
                'nullable',
                'string',
                'max:200',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:160',
            ],

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim(strip_tags($this->title)),
        ]);
    }
}