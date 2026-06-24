<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class ReportBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'reason' => [
                'required',
                'string',
                'max:500',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ];
    }
}