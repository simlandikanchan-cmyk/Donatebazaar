<?php

namespace Database\Factories;

use App\Models\GiftCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardFactory extends Factory
{
    protected $model = GiftCard::class;

    public function definition(): array
    {
        return [
            'code' => 'DNBZ-' . strtoupper($this->faker->bothify('????')) . '-' . strtoupper($this->faker->bothify('????')),
            'amount' => $this->faker->numberBetween(100, 10000),
            'theme' => $this->faker->randomElement(['purple', 'teal', 'coral', 'blue']),
            'sender_name' => $this->faker->name(),
            'sender_email' => $this->faker->safeEmail(),
            'recipient_name' => $this->faker->name(),
            'recipient_email' => $this->faker->safeEmail(),
            'message' => $this->faker->sentence(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'send_at' => now()->addDay(),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attrs) => ['payment_status' => 'completed', 'status' => 'sent']);
    }

    public function redeemed(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'redeemed']);
    }
}
