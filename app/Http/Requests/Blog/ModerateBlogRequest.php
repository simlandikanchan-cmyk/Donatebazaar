<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModerateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'status' => [
                'required',
                Rule::in([
                    'approved',
                    'rejected',
                    'flagged',
                    'archived',
                ]),
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ];
    }
}