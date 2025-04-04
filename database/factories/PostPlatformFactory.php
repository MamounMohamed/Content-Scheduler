<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostPlatform>
 */
class PostPlatformFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => \App\Models\Post::factory(),
            'platform_id' => \App\Models\Platform::factory(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
