<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'type' => 'trust',
            'address' => fake()->address(),
            'cause' => fake()->sentence(3),
            'founder_name' => fake()->name(),
            'linkedin' => fake()->url(),
            'website' => fake()->url(),
            'budget_range' => '1L-5L',
            'donor_strength' => 'Medium',
            'employee_strength' => '10-50',
            'has_crowdfunded' => false,
            'campaign_timeline' => '3 months',
        ];
    }
}
