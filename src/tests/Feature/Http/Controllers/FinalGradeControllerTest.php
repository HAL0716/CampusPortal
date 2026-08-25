<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Models\CourseOffering;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FinalGradeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_final_grades(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::CourseOfferingManagement])->create(),
        ])->create();

        $offering = CourseOffering::factory()->forTeacher(
            Teacher::factory()->for($user)->create()
        )->create();

        $this->actingAs($user)
            ->get(route('course-offerings.final-grades.index', $offering->id))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('FinalGrade/Index')
            );
    }
}
