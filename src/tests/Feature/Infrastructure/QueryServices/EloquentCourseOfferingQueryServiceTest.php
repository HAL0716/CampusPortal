<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\CourseOffering\Administration\DTOs\CourseOfferingDTO as AdministrationDTO;
use App\Application\Contexts\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Contexts\CourseOffering\Enrollment\DTOs\CourseOfferingDTO as EnrollmentDTO;
use App\Application\Contexts\CourseOffering\Management\DTOs\CourseOfferingDTO as ManagementDTO;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentCourseOfferingQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CourseOfferingQueryServiceInterface $queryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryService = app(CourseOfferingQueryServiceInterface::class);
    }

    public function test_can_find_for_administration(): void
    {
        $semester = Semester::factory()->create();
        $course = Course::factory()->create(['name' => '数学']);
        CourseOffering::factory()->create([
            'semester_id' => $semester->id,
            'course_id' => $course->id,
        ]);

        $result = $this->queryService->findForAdministration(
            new SemesterId($semester->id),
        );

        $this->assertCount(1, $result);
        $this->assertInstanceOf(AdministrationDTO::class, $result[0]);
        $this->assertSame($course->id, $result[0]->id);
        $this->assertSame('数学', $result[0]->name);
    }

    public function test_can_find_for_enrollment(): void
    {
        $semester = Semester::factory()->create();
        $student = Student::factory()->create();
        $course = Course::factory()->create(['name' => '数学']);
        $offering = CourseOffering::factory()->create([
            'semester_id' => $semester->id,
            'course_id' => $course->id,
        ]);

        Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $offering->id,
            'status' => EnrollmentStatus::ENROLLED,
        ]);

        $result = $this->queryService->findForEnrollment(
            new SemesterId($semester->id),
            new StudentId($student->id),
        );

        $this->assertCount(1, $result);
        $this->assertInstanceOf(EnrollmentDTO::class, $result[0]);
        $this->assertSame($offering->id, $result[0]->id);
        $this->assertSame('数学', $result[0]->name);
        $this->assertSame(EnrollmentStatus::ENROLLED, $result[0]->status);
    }

    public function test_for_enrollment_uses_latest_enrollment_for_same_course(): void
    {
        $previousSemester = Semester::factory()->create();
        $currentSemester = Semester::factory()->second()->create();
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $previousOffering = CourseOffering::factory()->create([
            'semester_id' => $previousSemester->id,
            'course_id' => $course->id,
        ]);

        $currentOffering = CourseOffering::factory()->create([
            'semester_id' => $currentSemester->id,
            'course_id' => $course->id,
        ]);

        Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $previousOffering->id,
            'status' => EnrollmentStatus::FAILED,
        ]);

        Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $currentOffering->id,
            'status' => EnrollmentStatus::ENROLLED,
        ]);

        $result = $this->queryService->findForEnrollment(
            new SemesterId($currentSemester->id),
            new StudentId($student->id),
        );

        $this->assertCount(1, $result);
        $this->assertSame($currentOffering->id, $result[0]->id);
        $this->assertSame(EnrollmentStatus::ENROLLED, $result[0]->status);
    }

    public function test_for_enrollment_returns_null_status_when_not_enrolled(): void
    {
        $semester = Semester::factory()->create();
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        CourseOffering::factory()->create([
            'semester_id' => $semester->id,
            'course_id' => $course->id,
        ]);

        $result = $this->queryService->findForEnrollment(
            new SemesterId($semester->id),
            new StudentId($student->id),
        );

        $this->assertCount(1, $result);
        $this->assertNull($result[0]->status);
    }

    public function test_for_management_returns_students_sorted_by_student_number(): void
    {
        $semester = Semester::factory()->create();
        $teacher = Teacher::factory()->create();
        $course = Course::factory()->create();
        $course->teachers()->attach($teacher->id);

        $offering = CourseOffering::factory()->create([
            'semester_id' => $semester->id,
            'course_id' => $course->id,
        ]);

        $student2 = Student::factory()->create([
            'student_number' => 'B002',
        ]);

        $student1 = Student::factory()->create([
            'student_number' => 'A001',
        ]);

        Enrollment::factory()->create([
            'student_id' => $student2->id,
            'course_offering_id' => $offering->id,
            'status' => EnrollmentStatus::ENROLLED,
        ]);

        Enrollment::factory()->create([
            'student_id' => $student1->id,
            'course_offering_id' => $offering->id,
            'status' => EnrollmentStatus::COMPLETED,
        ]);

        $result = $this->queryService->findForManagement(
            new SemesterId($semester->id),
            new TeacherId($teacher->id),
        );

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ManagementDTO::class, $result[0]);
        $this->assertCount(2, $result[0]->enrollments);

        $this->assertSame(
            'A001',
            $result[0]->enrollments[0]->studentNumber,
        );

        $this->assertSame(
            'B002',
            $result[0]->enrollments[1]->studentNumber,
        );
    }
}
