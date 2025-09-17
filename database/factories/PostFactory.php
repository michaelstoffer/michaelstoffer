<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'title' => fake()->sentence(),
            'excerpt' => fake()->optional()->text(400),
            'body' => fake()->paragraphs(3, true),
            'cover' => fake()->optional()->imageUrl(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => null,
        ];
    }
}
