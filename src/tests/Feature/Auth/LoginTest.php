<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }

    public function test_user_can_login(): void
    {
        $password = 'password';

        $user = $this->createUser([
            'password' => $password,
        ]);

        $response = $this->post(route('login.submit'), [
            'login_id' => $user->login_id,
            'password' => $password,
        ]);

        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $password = 'password';

        $user = $this->createUser([
            'password' => $password,
        ]);

        foreach ([
            [
                'login_id' => $user->login_id,
                'password' => 'wrong-password',
            ],
            [
                'login_id' => 'wrong-login-id',
                'password' => $password,
            ],
        ] as $credentials) {
            $this->post(route('login.submit'), $credentials);

            $this->assertGuest();
        }
    }

    private function createUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'login_id' => 'test_id',
            'password' => 'password',
        ], $attributes));
    }
}
