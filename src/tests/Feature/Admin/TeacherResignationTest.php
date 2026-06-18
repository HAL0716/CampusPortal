<?php

namespace Tests\Feature\Admin;

use App\Enums\TeacherStatus;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherResignationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_resign_teacher(): void
    {
        $admin = User::factory()->admin()->create();

        $department = Department::factory()->create();

        $teacherUser = User::factory()->teacher()->create();

        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'department_id' => $department->id,
        ]);

        $this->actingAs($admin)
            ->patch(route('teachers.resign', $teacher))
            ->assertRedirect(route('teachers.index'));

        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'status' => TeacherStatus::RESIGNED->value,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $teacherUser->id,
        ]);
    }

    public function test_non_admin_cannot_resign_teacher(): void
    {
        $user = User::factory()->teacher()->create();

        $department = Department::factory()->create();

        $teacherUser = User::factory()->teacher()->create();

        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'department_id' => $department->id,
        ]);

        $this->actingAs($user)
            ->patch(route('teachers.resign', $teacher))
            ->assertForbidden();

        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'status' => TeacherStatus::ACTIVE->value,
        ]);
    }
}
