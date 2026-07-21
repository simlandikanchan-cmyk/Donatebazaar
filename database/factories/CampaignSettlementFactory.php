<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignSettlementFactory extends Factory
{
    protected $model = CampaignSettlement::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'organization_id' => Organization::factory(),
            'gross_amount' => 1000.00,
            'platform_fee' => 50.00,
            'net_amount' => 950.00,
            'status' => 'requested',
            'correlation_id' => $this->faker->uuid(),
            'trace_id' => $this->faker->uuid(),
        ];
    }
}
