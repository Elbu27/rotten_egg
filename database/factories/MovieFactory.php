<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Action', 'Comedy', 'Romance', 'Horror', 'Sci-Fi', 'Drama'];
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'poster' => fake()->imageUrl(400, 600, 'movies'),
            'trailer_url' => fake()->url(),
            'is_restricted' => fake()->boolean(30), // about 30% restricted
            'user_id' => \App\Models\User::factory()->state(['role' => 'producer']),
            'type' => fake()->randomElement($types),
        ];
    }
}
