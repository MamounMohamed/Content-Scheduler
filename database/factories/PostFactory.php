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
        $randomUsers = [1,1,2];

        return [
            'title' =>  $this->faker->sentence(3),
            'content' => $this->faker->paragraph(3),
            'image_url' => $this->faker->imageUrl(),
            'scheduled_time' => $this->faker->dateTimeBetween('now', '+3 months'),
            'status' => $this->faker->randomElement(['draft', 'scheduled', 'published']),
            'user_id' => $this->faker->randomElement($randomUsers),
        ];
    }
}
