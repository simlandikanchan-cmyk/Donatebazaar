<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycRejectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ];
    }
}
