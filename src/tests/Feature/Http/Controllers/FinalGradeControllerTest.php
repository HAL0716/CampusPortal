<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FinalGradeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_can_view_final_grades(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([
                Permission::factory()->withName(PermissionType::FinalGradeCreate)->create(),
            ])->create(),
        ])->create();

        $offering = CourseOffering::factory()
            ->forTeachers([Teacher::factory()->for($user)->create()])
            ->create();

        $enrollment = Enrollment::factory()
            ->create(['course_offering_id' => $offering->id]);

        $this->actingAs($user)
            ->get(route('course-offerings.final-grades.index', $offering))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('FinalGrade/Index')
                ->has('enrollments')
                ->where('enrollments.0.enrollmentId', $enrollment->id)
            );
    }

    public function test_index_cannot_view_final_grades_without_permission(): void
    {
        $user = User::factory()->create();

        $offering = CourseOffering::factory()
            ->forTeachers([Teacher::factory()->for($user)->create()])
            ->create();

        $this->actingAs($user)
            ->get(route('course-offerings.final-grades.index', $offering))
            ->assertForbidden();
    }
}
