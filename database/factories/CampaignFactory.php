<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => 'user',
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'slug' => $this->faker->unique()->slug(4),
            'goal_amount' => 100000.00,
            'raised_amount' => 0.00,
            'campaign_state' => 'active',
            'is_featured' => false,
            'is_urgent' => false,
            'enable_products' => false,
            'allow_custom_products' => false,
        ];
    }
}
