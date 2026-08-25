<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('COUPON-####')),
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_amount' => 100,
            'max_discount' => 500,
            'usage_limit' => 100,
            'used_count' => 0,
            'is_active' => true,
            'expires_at' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
        ];
    }

    public function fixed(): static
    {
        return $this->state(fn (array $attrs) => ['discount_type' => 'fixed', 'discount_value' => 50]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attrs) => ['expires_at' => now()->subDay()]);
    }
}
