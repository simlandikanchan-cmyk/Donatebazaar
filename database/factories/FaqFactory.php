<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(['General', 'Donations', 'Campaigns', 'Payments']),
            'question' => $this->faker->sentence() . '?',
            'answer' => $this->faker->paragraph(),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }
}
