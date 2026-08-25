<?php

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'subscribed_at' => now(),
            'unsubscribe_token' => \Illuminate\Support\Str::random(64),
        ];
    }

    public function unsubscribed(): static
    {
        return $this->state(fn (array $attrs) => [
            'unsubscribed_at' => now()->subDay(),
        ]);
    }
}
