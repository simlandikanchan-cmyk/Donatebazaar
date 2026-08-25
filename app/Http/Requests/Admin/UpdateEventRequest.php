<?php

namespace App\Http\Requests\Admin;

use App\Models\Event;

class UpdateEventRequest extends EventFormRequest
{
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'category_id' => ['nullable', 'exists:categories,id'],
            // All valid statuses an admin can manually set from edit page
            'status' => [
                'required',
                'in:draft,active,'.implode(',', [
                    Event::STATUS_PENDING,
                    Event::STATUS_COMPLETED,
                    Event::STATUS_CANCELLED,
                    Event::STATUS_EXPIRED,
                ]),
            ],
        ]);
    }
}