<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Comment;
use App\Models\Rating;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Producers & normal users
        $producers = User::factory(2)->create(['role' => 'producer']);
        $users = User::factory(5)->create(['role' => 'user']);

        // Movies 
        $movies = Movie::factory(10)->create();

        // Comments & Ratings
        Comment::factory(30)->create();
        Rating::factory(50)->create();

        // Comment Likes
        \App\Models\CommentLike::factory(50)->create();

        //Add movie into favourite
        $allMovies = Movie::pluck('id');

        foreach ($users as $user) {
            $randomMovies = $allMovies->random(rand(2, 5));
            $user->favorites()->syncWithoutDetaching($randomMovies);
        }

    }
}
