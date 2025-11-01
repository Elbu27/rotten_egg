<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        \App\Models\User::factory(2)->create(['role' => 'producer']);
        \App\Models\User::factory(5)->create(['role' => 'user']);

        // Movies (auto-linked to producers)
        \App\Models\Movie::factory(10)->create();

        // Comments & Ratings
        \App\Models\Comment::factory(30)->create();
        \App\Models\Rating::factory(50)->create();

        \App\Models\CommentLike::factory(50)->create();

        \App\Models\Favorite::factory(30)->create();


    }
}
