<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Platform>
 */
class PlatformFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $platforms = ['twitter', 'instagram', 'facebook', 'tiktok', 'youtube', 'linkedin', 'pinterest', 'tumblr', 'reddit', 'telegram'];
        $names =[];
        foreach ($platforms as $platform) {
            $names[] = ucfirst($platform);
        }

        return [
            'name' => $this->faker->randomElement($names),
            'type' => $this->faker->randomElement($platforms),
        ];
    }
}
