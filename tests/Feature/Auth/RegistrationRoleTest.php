<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegistrationRoleTest extends TestCase
{
    use RefreshDatabase;

    private function registrationData(): array
    {
        return [
            'name' => 'Test User',
            'email' => 'registration@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    }

    public static function publicRoles(): array
    {
        return ['user' => ['user'], 'producer' => ['producer']];
    }

    #[DataProvider('publicRoles')]
    public function test_public_roles_can_register(string $role): void
    {
        $this->post(route('register'), [...$this->registrationData(), 'role' => $role])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('movies.index'));

        $user = User::sole();
        $this->assertSame($role, $user->role);
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_without_a_role_defaults_to_an_ordinary_user(): void
    {
        $this->post(route('register'), $this->registrationData())
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('movies.index'));

        $user = User::sole();
        $this->assertSame('user', $user->role);
        $this->assertAuthenticatedAs($user);
    }

    public static function forbiddenRoles(): array
    {
        return [
            'administrator' => ['admin'],
            'unknown role' => ['superuser'],
            'array payload' => [['admin']],
        ];
    }

    #[DataProvider('forbiddenRoles')]
    public function test_invalid_public_roles_are_rejected_without_creating_an_account(mixed $role): void
    {
        $this->post(route('register'), [...$this->registrationData(), 'role' => $role])
            ->assertSessionHasErrors('role');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }
}
