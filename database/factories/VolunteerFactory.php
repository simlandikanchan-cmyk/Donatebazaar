<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

class VolunteerFactory extends Factory
{
    protected $model = Volunteer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone' => $this->faker->numerify('##########'),
            'bio' => $this->faker->paragraph(),
            'skills' => [$this->faker->word(), $this->faker->word()],
            'availability' => $this->faker->randomElement(['full_time', 'part_time', 'weekends']),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'is_verified' => false,
        ];
    }
}
