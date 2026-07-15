<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class DashboardControllerTest extends TestCase
{
    use CreatesModelUser;
    use RefreshDatabase;

    public function test_shows_dashboard_page(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Index')
                ->where('auth.user.name', $user->name)
            );
    }

    public function test_redirects_guest_to_login(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }
}
