<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'author_role' => 'admin',
            'title' => $this->faker->sentence(5),
            'content' => $this->faker->paragraphs(3, true),
            'status' => Blog::STATUS_DRAFT,
            'views_count' => 0,
            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => 0,
            'reports_count' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => Blog::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attrs) => ['status' => Blog::STATUS_PENDING]);
    }
}
