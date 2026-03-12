<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'url' => fake()->url(),
            'platform' => fake()->randomElement(['YouTube','Vimeo','Internal']),
            'length' => fake()->numberBetween(5,30)
        ];
    }
}
