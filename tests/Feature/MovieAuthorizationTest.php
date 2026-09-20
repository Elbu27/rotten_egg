<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MovieAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public static function creationRoles(): array
    {
        return [
            'ordinary user' => ['user', false],
            'producer' => ['producer', true],
            'administrator' => ['admin', true],
        ];
    }

    private function movieData(): array
    {
        return [
            'title' => 'Authorization test movie',
            'description' => 'A movie created by a feature test.',
            'release_year' => 2025,
            'age_rating' => 12,
            'genres' => [],
        ];
    }

    public function test_guests_cannot_open_the_creation_page_or_create_movies(): void
    {
        $this->get(route('movies.create'))->assertRedirect(route('login'));
        $this->post(route('movies.store'), $this->movieData())
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('movies', 0);
    }

    #[DataProvider('creationRoles')]
    public function test_creation_page_and_navigation_follow_the_role_policy(string $role, bool $allowed): void
    {
        $this->actingAs(User::factory()->create(['role' => $role]));

        $this->get(route('movies.create'))->assertStatus($allowed ? 200 : 403);

        $index = $this->get(route('movies.index'))->assertOk();
        $link = 'href="'.route('movies.create').'"';

        if ($allowed) {
            $index->assertSee($link, false);
        } else {
            $index->assertDontSee($link, false);
        }
    }

    #[DataProvider('creationRoles')]
    public function test_movie_creation_follows_the_role_policy(string $role, bool $allowed): void
    {
        $user = User::factory()->create(['role' => $role]);
        $otherUser = User::factory()->create(['role' => 'producer']);

        $response = $this->actingAs($user)->post(route('movies.store'), [
            ...$this->movieData(),
            'user_id' => $otherUser->id,
        ]);

        if (! $allowed) {
            $response->assertForbidden();
            $this->assertDatabaseCount('movies', 0);

            return;
        }

        $response->assertSessionHasNoErrors();
        $movie = Movie::sole();
        $response->assertRedirect(route('movies.show', $movie));
        $this->assertSame($user->id, $movie->user_id);
        $this->assertDatabaseHas('movies', [
            'id' => $movie->id,
            'title' => $this->movieData()['title'],
        ]);
    }

    public static function nonOwnerRoles(): array
    {
        return [
            'ordinary user' => ['user'],
            'another producer' => ['producer'],
        ];
    }

    #[DataProvider('nonOwnerRoles')]
    public function test_non_owners_cannot_edit_update_or_delete_a_movie(string $role): void
    {
        $movie = Movie::factory()->create();
        $original = $movie->fresh()->getAttributes();
        $this->actingAs(User::factory()->create(['role' => $role]));

        $this->get(route('movies.edit', $movie))->assertForbidden();
        $this->put(route('movies.update', $movie), $this->movieData())->assertForbidden();
        $this->delete(route('movies.destroy', $movie))->assertForbidden();

        $this->assertDatabaseCount('movies', 1);
        $this->assertSame($original, $movie->fresh()->getAttributes());
    }

    public function test_guests_cannot_modify_an_existing_movie(): void
    {
        $movie = Movie::factory()->create();
        $original = $movie->fresh()->getAttributes();

        $this->get(route('movies.edit', $movie))->assertRedirect(route('login'));
        $this->put(route('movies.update', $movie), $this->movieData())->assertRedirect(route('login'));
        $this->delete(route('movies.destroy', $movie))->assertRedirect(route('login'));

        $this->assertSame($original, $movie->fresh()->getAttributes());
    }

    public static function authorizedEditors(): array
    {
        return [
            'owner' => [false],
            'administrator who is not the owner' => [true],
        ];
    }

    #[DataProvider('authorizedEditors')]
    public function test_owners_and_administrators_can_edit_update_and_delete_movies(bool $asAdmin): void
    {
        $movie = Movie::factory()->create();
        $user = $asAdmin ? User::factory()->create(['role' => 'admin']) : $movie->user;
        $this->actingAs($user);

        $this->get(route('movies.edit', $movie))->assertOk();
        $this->put(route('movies.update', $movie), $this->movieData())
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('movies.show', $movie));

        $this->assertDatabaseHas('movies', [
            'id' => $movie->id,
            'title' => $this->movieData()['title'],
            'user_id' => $movie->user_id,
        ]);

        $this->delete(route('movies.destroy', $movie))->assertRedirect(route('movies.index'));
        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }
}
