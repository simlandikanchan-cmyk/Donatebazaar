<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'action' => 'required|string|in:approve,reject,pause,delete,publish,read',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('reason', 'required|string|min:10|max:500', function ($input) {
            return $input->action === 'reject';
        });
    }
}
