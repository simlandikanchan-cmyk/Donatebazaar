<?php

namespace App\Http\Requests\Admin;

class StoreBlogRequest extends BlogFormRequest
{
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'action' => ['nullable', 'string', 'in:draft,schedule,publish'],
            'status' => ['nullable', 'string', 'in:draft,pending,approved,rejected,archived,flagged'],
            'publish_now' => ['nullable', 'boolean'],
            'schedule_toggle' => ['nullable', 'boolean'],
            'scheduled_at_date' => ['nullable', 'date'],
            'scheduled_at_time' => ['nullable', 'string'],
            'syndicate_newsletter' => ['nullable', 'boolean'],
            'author_override' => ['nullable', 'string', 'max:100'],
            'author_role_override' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
