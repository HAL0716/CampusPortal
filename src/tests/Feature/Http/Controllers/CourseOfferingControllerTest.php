<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Academic\Enums\Term;
use App\Domain\Permission\Enums\PermissionType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Support\Clock\UseClock;
use Tests\TestCase;

final class CourseOfferingControllerTest extends TestCase
{
    use RefreshDatabase;
    use UseClock;

    private const DATE = '2025-04-01';

    protected function setUp(): void
    {
        parent::setUp();

        $this->useClock(self::DATE);

        Semester::create([
            'academic_year' => '2025',
            'term' => Term::FIRST,
            'start_date' => self::DATE,
            'end_date' => '2025-07-31',
        ]);
    }

    private function userWithPermission(PermissionType $permission): User
    {
        $user = User::factory()->create();

        $permission = Permission::factory()->create([
            'name' => $permission->value,
        ]);

        $role = Role::factory()->create();
        $role->permissions()->attach($permission);

        $user->roles()->attach($role);

        return $user;
    }

    private function assertInertiaPage(User $user, string $route, string $component): void
    {
        $response = $this->actingAs($user)->get(route($route));

        $response->assertStatus(200);
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component($component)
                ->has('offerings')
        );
    }
}
