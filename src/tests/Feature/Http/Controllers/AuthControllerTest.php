<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class AuthControllerTest extends TestCase
{
    use CreatesModelUser;
    use RefreshDatabase;

    public function test_shows_login_page(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/Login')
                ->where('auth.user', null)
            );
    }

    public function test_login_user_successfully(): void
    {
        $user = $this->createUser();

        $this->post(route('login.store'), [
            'email' => $this->userEmail(),
            'password' => $this->userPassword(),
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_fails_to_login_with_invalid_credentials(): void
    {
        $this->post(route('login.store'), [
            'email' => $this->userEmail(),
            'password' => $this->userPassword(),
        ])
            ->assertSessionHasErrors('email')
            ->assertSessionHasInput('email');

        $this->assertGuest();
    }

    public function test_logout_user(): void
    {
        $this->actingAs($this->createUser());

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
