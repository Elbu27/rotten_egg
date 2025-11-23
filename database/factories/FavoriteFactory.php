<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{

    protected $model = Favorite::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'movie_id' => Movie::factory(),
        ];
    }
}