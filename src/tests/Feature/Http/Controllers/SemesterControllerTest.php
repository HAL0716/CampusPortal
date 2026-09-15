<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Academic\Enums\Term;
use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Role\Enums\RoleType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Semester;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SemesterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }

    public function test_can_view_semesters(): void
    {
        $admin = $this->createAdmin();

        [$latest, $previous] = Semester::factory()
            ->count(2)
            ->sequence(
                [
                    'academic_year' => '2026',
                    'term' => Term::SECOND,
                    'start_date' => '2026-09-01',
                    'end_date' => '2026-12-31',
                ],
                [
                    'academic_year' => '2026',
                    'term' => Term::FIRST,
                    'start_date' => '2026-04-01',
                    'end_date' => '2026-08-31',
                ],
            )
            ->create();

        $this->actingAs($admin)
            ->get(route('semesters.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Semester/Index')
                ->has('semesters', 2)
                ->where('semesters.0.id', $latest->id)
                ->where('semesters.1.id', $previous->id)
            );
    }

    public function test_cannot_view_semesters_without_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('semesters.index'))
            ->assertForbidden();
    }

    private function createAdmin(): User
    {
        $role = Role::query()
            ->where('name', RoleType::ADMIN)
            ->firstOrFail();

        $permission = Permission::query()
            ->where('name', PermissionType::SemesterManage)
            ->firstOrFail();

        $role->permissions()->syncWithoutDetaching($permission);

        return User::factory()
            ->withRoles([$role])
            ->create();
    }
}
