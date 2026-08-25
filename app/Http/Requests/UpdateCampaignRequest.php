<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;

class UpdateCampaignRequest extends CampaignFormRequest
{
    public function authorize(): bool
    {
        $campaign = $this->route('campaign');

        if (! $campaign || $campaign->user_id !== auth()->id()) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return true;
    }

    public function rules(): array
    {
        return $this->baseRules();
    }
}