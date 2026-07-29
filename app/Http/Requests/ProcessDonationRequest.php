<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessDonationRequest extends FormRequest
{
    const MIN_AMOUNT = 1;
    const MAX_AMOUNT = 500000;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', "min:" . self::MIN_AMOUNT, "max:" . self::MAX_AMOUNT],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Donation amount is required.',
            'amount.numeric' => 'Donation amount must be a number.',
            'amount.min' => 'Minimum donation amount is ₹' . self::MIN_AMOUNT . '.',
            'amount.max' => 'Donation amount cannot exceed ₹' . self::MAX_AMOUNT . '.',
        ];
    }
}
