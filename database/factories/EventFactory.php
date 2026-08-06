<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'event_date' => $this->faker->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'status' => Event::STATUS_PENDING,
            'raised_amount' => 0,
            'registered_count' => 0,
        ];
    }
}
