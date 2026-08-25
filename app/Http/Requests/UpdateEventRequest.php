<?php

namespace App\Http\Requests;

class UpdateEventRequest extends EventFormRequest
{
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'max_participants' => ['required', 'integer', 'min:1'],
        ]);
    }
}