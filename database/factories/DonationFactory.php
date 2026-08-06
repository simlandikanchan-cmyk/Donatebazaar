<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'user_id' => User::factory(),
            'total_amount' => $this->faker->numberBetween(100, 50000),
            'net_amount' => $this->faker->numberBetween(90, 49000),
            'payment_status' => 'pending',
            'currency' => 'INR',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attrs) => ['payment_status' => 'completed']);
    }
}
