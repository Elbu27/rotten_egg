<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentLikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'comment_id' => \App\Models\Comment::factory(),
            'is_like' => fake()->boolean(80), // 80% chance of like
        ];
    }
}