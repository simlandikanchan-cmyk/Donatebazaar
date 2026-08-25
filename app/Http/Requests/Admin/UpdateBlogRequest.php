<?php

namespace App\Http\Requests\Admin;

class UpdateBlogRequest extends BlogFormRequest
{
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'status' => ['nullable', 'string', 'in:draft,pending,approved,rejected,archived,flagged'],
        ]);
    }
}
