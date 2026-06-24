<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'content' => [
                'required',
                'string',
                'min:2',
                'max:1000',
            ],

            'parent_id' => [
                'nullable',
                'integer',
                'exists:blog_comments,id',
            ],

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'content' => trim(
                strip_tags($this->input('content'))
            ),
        ]);
    }
}