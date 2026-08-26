<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Domain\Permission\Enums\PermissionType;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_enroll_offering(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([
                Permission::factory()->withName(PermissionType::EnrollmentManage)->create(),
            ])->create(),
        ])->create();

        $student = Student::factory()->for($user)->create();

        $offering = CourseOffering::factory()->create();

        $this->actingAs($user)
            ->post(route('course-offerings.enroll', $offering))
            ->assertRedirectBack();

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_offering_id' => $offering->id,
        ]);
    }

    public function test_cannot_enroll_offering_without_permission(): void
    {
        $user = User::factory()->create();

        $offering = CourseOffering::factory()->create();

        $this->actingAs($user)
            ->post(route('course-offerings.enroll', $offering))
            ->assertForbidden();
    }

    public function test_can_drop_course(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([
                Permission::factory()->withName(PermissionType::EnrollmentManage)->create(),
            ])->create(),
        ])->create();

        $enrollment = Enrollment::factory()->create([
            'student_id' => Student::factory()->for($user)->create()->id,
            'course_offering_id' => CourseOffering::factory()->create()->id,
        ]);

        $this->actingAs($user)
            ->post(route('course-offerings.drop', $enrollment->course_offering_id), [
                'enrollment_id' => $enrollment->id,
            ])->assertRedirectBack();

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => EnrollmentStatus::DROPPED->value,
        ]);
    }

    public function test_cannot_drop_course_without_permission(): void
    {
        $user = User::factory()->create();

        $enrollment = Enrollment::factory()->create([
            'student_id' => Student::factory()->for($user)->create()->id,
            'course_offering_id' => CourseOffering::factory()->create()->id,
        ]);

        $this->actingAs($user)
            ->post(route('course-offerings.drop', $enrollment->course_offering_id), [
                'enrollment_id' => $enrollment->id,
            ])->assertForbidden();
    }

    public function test_can_complete_enrollment(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([
                Permission::factory()->withName(PermissionType::FinalGradeCreate)->create(),
            ])->create(),
        ])->create();

        $teacher = Teacher::factory()->for($user)->create();

        $enrollment = Enrollment::factory()->create([
            'student_id' => Student::factory()->for($user)->create()->id,
            'course_offering_id' => CourseOffering::factory()->forTeachers([$teacher])->create()->id,
        ]);

        $this->actingAs($user)
            ->post(route('enrollments.complete', $enrollment), [
                'grade' => FinalGradeType::A->value,
            ])->assertRedirectBack();

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => EnrollmentStatus::COMPLETED,
        ]);

        $this->assertDatabaseHas('final_grades', [
            'enrollment_id' => $enrollment->id,
            'grade' => FinalGradeType::A,
        ]);
    }

    public function test_cannot_complete_enrollment_without_permission(): void
    {
        $user = User::factory()->create();

        $teacher = Teacher::factory()->for($user)->create();

        $enrollment = Enrollment::factory()->create([
            'student_id' => Student::factory()->for($user)->create()->id,
            'course_offering_id' => CourseOffering::factory()->forTeachers([$teacher])->create()->id,
        ]);

        $this->actingAs($user)
            ->post(route('enrollments.complete', $enrollment), [
                'grade' => FinalGradeType::A->value,
            ])->assertForbidden();
    }
}
