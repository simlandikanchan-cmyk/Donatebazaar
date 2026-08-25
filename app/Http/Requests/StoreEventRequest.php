<?php

namespace App\Http\Requests;

class StoreEventRequest extends EventFormRequest
{
    public function rules(): array
    {
        return $this->baseRules();
    }
}