<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Academic\Enums\Term;
use App\Domain\Permission\Enums\PermissionType;
use App\Models\CourseOffering;
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

    public function test_can_view_course_offerings(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::CourseOfferingView])->create(),
        ])->create();

        $this->actingAs($user)
            ->get(route('course-offerings.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('CourseOffering/Index')
                ->has('offerings')
            );
    }

    public function test_can_view_course_offering_detail(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::CourseOfferingView])->create(),
        ])->create();

        $offering = CourseOffering::factory()->create();

        $this->actingAs($user)
            ->get(route('course-offerings.show', $offering->id))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('CourseOffering/Show')
                ->has('offering')
            );
    }
}
