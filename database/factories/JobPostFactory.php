<?php

namespace Database\Factories;

use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    protected $model = JobPost::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement(['full-time', 'part-time', 'contract', 'internship']),
            'location' => $this->faker->city(),
            'status' => 'active',
            'vacancies' => $this->faker->numberBetween(1, 5),
            'views_count' => 0,
            'applications_count' => 0,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'closed']);
    }
}
