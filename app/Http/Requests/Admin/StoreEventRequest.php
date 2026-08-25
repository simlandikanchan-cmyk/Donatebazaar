<?php

namespace App\Http\Requests\Admin;

class StoreEventRequest extends EventFormRequest
{
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            // draft = saved but not live, active = published/live
            'status' => ['nullable', 'in:draft,active'],
            'send_notification' => ['nullable', 'boolean'],
        ]);
    }
}