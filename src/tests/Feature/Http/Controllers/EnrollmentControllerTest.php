<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\FinalGrade\FinalGradeType;
use App\Domain\Permission\Enums\PermissionType;
use App\Models\Course;
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

    public function test_can_enroll_course(): void
    {
        $user = $this->userWithPermission(PermissionType::CourseOfferingEnrollment);
        Student::factory()->for($user)->create();
        $courseOffering = CourseOffering::factory()->create();

        $this->actingAs($user);
        $response = $this->post(route('course-offerings.enroll', $courseOffering));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $user->student->id,
            'course_offering_id' => $courseOffering->id,
            'status' => EnrollmentStatus::ENROLLED->value,
        ]);
    }

    public function test_can_drop_course(): void
    {
        $user = $this->userWithPermission(PermissionType::CourseOfferingEnrollment);
        $student = Student::factory()->for($user)->create();
        $courseOffering = CourseOffering::factory()->create();

        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $courseOffering->id,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('course-offerings.drop', $courseOffering));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => EnrollmentStatus::DROPPED->value,
        ]);
    }

    public function test_can_complete_enrollment(): void
    {
        $user = $this->userWithPermission(PermissionType::CourseOfferingManagement);
        $teacher = Teacher::factory()->for($user)->create();

        $course = Course::factory()->create();
        $course->teachers()->attach($teacher->id);

        $courseOffering = CourseOffering::factory()->create(['course_id' => $course->id]);
        $student = Student::factory()->create();

        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $courseOffering->id,
            'status' => EnrollmentStatus::ENROLLED,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('enrollments.complete', $enrollment), [
            'grade' => FinalGradeType::A->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => EnrollmentStatus::COMPLETED,
        ]);

        $this->assertDatabaseHas('final_grades', [
            'enrollment_id' => $enrollment->id,
            'grade' => FinalGradeType::A->value,
        ]);
    }

    private function userWithPermission(PermissionType $permission): User
    {
        $user = User::factory()->create();

        $permission = Permission::factory()->create(['name' => $permission->value]);
        $role = Role::factory()->create();

        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        return $user;
    }
}
