<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_dashboard(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::DashboardView])->create(),
        ])->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Index')
            );
    }

    public function test_guest_cannot_view_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_user_without_dashboard_permission_cannot_view_dashboard(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->create(),
        ])->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }
}
