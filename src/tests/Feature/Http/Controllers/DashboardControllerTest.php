<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_dashboard(): void
    {
        $permission = Permission::factory()->create([
            'name' => PermissionType::DashboardView->value,
        ]);

        $role = Role::factory()->create();

        $role->permissions()->attach($permission);

        $user = User::factory()->create();

        $user->roles()->attach($role);

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
        $role = Role::factory()->create();

        $user = User::factory()->create();

        $user->roles()->attach($role);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }
}
