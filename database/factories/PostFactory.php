<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
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
            'title' => $this->faker->word(),
            'content' => $this->faker->text(),
            'image_url' => $this->faker->imageUrl(),
            'scheduled_time' => $this->faker->dateTimeBetween('now', '+3 months'),
            'status' => $this->faker->randomElement(['draft', 'scheduled', 'published']),
            'user_id' => 1,
        ];
    }
}
