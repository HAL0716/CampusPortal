<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Academic\Term;
use App\Domain\Permission\PermissionType;
use App\Models\Semester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Permission\CreatesModelPermission;
use Tests\Support\User\CreatesModelUser;
use Tests\TestCase;

final class DashboardControllerTest extends TestCase
{
    use CreatesModelPermission;
    use CreatesModelUser;
    use RefreshDatabase;

    public function test_shows_dashboard_page(): void
    {
        $user = $this->createUser();

        $this->createPermission(
            $user,
            PermissionType::DashboardView
        );

        Semester::create([
            'academic_year' => '2025',
            'term' => Term::FIRST,
            'start_date' => '2025-04-01',
            'end_date' => '2025-07-31',
        ]);

        $this->actingAs($user);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Index')
                ->where('auth.user.name', $user->name)
                ->has('auth.user.permissions', 1)
                ->where(
                    'auth.user.permissions.0',
                    PermissionType::DashboardView->value
                )
                ->has('offerings')
            );
    }

    public function test_forbids_user_without_permission(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $this->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_redirects_guest_to_login(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }
}
