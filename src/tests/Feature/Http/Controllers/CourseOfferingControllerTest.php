<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Models\CourseOffering;
use App\Models\Role;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Clock\UseClock;
use Tests\TestCase;

final class CourseOfferingControllerTest extends TestCase
{
    use RefreshDatabase;
    use UseClock;

    public function test_can_view_course_offerings(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::CourseOfferingView])->create(),
        ])->create();

        $semester = Semester::factory()->create();

        $this->useClock($semester->start_date->toDateString());

        $offering = CourseOffering::factory()->for($semester)->create();

        $this->actingAs($user)
            ->get(route('course-offerings.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('CourseOffering/Index')
                ->has('offerings')
                ->where('offerings.0.id', $offering->id)
            );
    }

    public function test_cannot_view_course_offerings_without_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('course-offerings.index'))
            ->assertForbidden();
    }

    public function test_can_view_course_offering_detail(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::CourseOfferingView])->create(),
        ])->create();

        $offering = CourseOffering::factory()->create();

        $this->actingAs($user)
            ->get(route('course-offerings.show', $offering))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('CourseOffering/Show')
                ->has('offering')
                ->where('offering.id', $offering->id)
            );
    }

    public function test_cannot_view_course_offering_detail_without_permission(): void
    {
        $user = User::factory()->create();

        $offering = CourseOffering::factory()->create();

        $this->actingAs($user)
            ->get(route('course-offerings.show', $offering))
            ->assertForbidden();
    }
}
