<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\FinalGrade\DTOs\EnrollmentForFinalGradeDTO;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Infrastructure\QueryServices\EloquentEnrollmentQueryService;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\FinalGrade;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentEnrollmentQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    private function queryService(): EloquentEnrollmentQueryService
    {
        return app(EloquentEnrollmentQueryService::class);
    }

    public function test_list_for_final_grade_returns_enrollments_without_final_grade(): void
    {
        $offering = CourseOffering::factory()->create();

        $student = Student::factory()->create();

        $enrollment = Enrollment::factory()
            ->for($student)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $result = $this->queryService()->listForFinalGrade(
            new CourseOfferingId($offering->id),
        );

        self::assertCount(1, $result);
        self::assertContainsOnlyInstancesOf(EnrollmentForFinalGradeDTO::class, $result);
        self::assertSame($enrollment->id, $result[0]->enrollmentId);
        self::assertSame($student->student_number, $result[0]->studentNumber);
        self::assertNull($result[0]->finalGrade);
    }

    public function test_list_for_final_grade_returns_final_grade(): void
    {
        $offering = CourseOffering::factory()->create();

        $student = Student::factory()->create();

        $enrollment = Enrollment::factory()
            ->for($student)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $finalGrade = FinalGrade::factory()
            ->for($enrollment)
            ->create();

        $result = $this->queryService()->listForFinalGrade(
            new CourseOfferingId($offering->id),
        );

        self::assertCount(1, $result);

        self::assertSame($enrollment->id, $result[0]->enrollmentId);
        self::assertSame($student->student_number, $result[0]->studentNumber);
        self::assertSame($finalGrade->grade, $result[0]->finalGrade);
    }

    public function test_list_for_final_grade_excludes_dropped_enrollment(): void
    {
        $offering = CourseOffering::factory()->create();

        $enrolledStudent = Student::factory()->create();
        $droppedStudent = Student::factory()->create();

        $enrolledEnrollment = Enrollment::factory()
            ->for($enrolledStudent)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $droppedEnrollment = Enrollment::factory()
            ->for($droppedStudent)
            ->for($offering)
            ->status(EnrollmentStatus::DROPPED)
            ->create();

        $result = $this->queryService()->listForFinalGrade(
            new CourseOfferingId($offering->id),
        );

        self::assertCount(1, $result);

        self::assertSame($enrolledEnrollment->id, $result[0]->enrollmentId);
        self::assertNotSame($droppedEnrollment->id, $result[0]->enrollmentId);
    }

    public function test_list_for_final_grade_returns_only_enrollments_for_specified_course_offering(): void
    {
        $semester = Semester::factory()->create();

        $offering = CourseOffering::factory()->for($semester)->create();
        $otherOffering = CourseOffering::factory()->for($semester)->create();

        $student = Student::factory()->create();
        $otherStudent = Student::factory()->create();

        $enrollment = Enrollment::factory()
            ->for($student)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $otherEnrollment = Enrollment::factory()
            ->for($otherStudent)
            ->for($otherOffering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $result = $this->queryService()->listForFinalGrade(
            new CourseOfferingId($offering->id),
        );

        self::assertCount(1, $result);
        self::assertSame($enrollment->id, $result[0]->enrollmentId);
        self::assertNotSame($otherEnrollment->id, $result[0]->enrollmentId);
    }

    public function test_list_for_final_grade_returns_enrollments_ordered_by_student_number(): void
    {
        $offering = CourseOffering::factory()->create();

        $student2 = Student::factory()->create([
            'student_number' => '20260002',
        ]);

        $student1 = Student::factory()->create([
            'student_number' => '20260001',
        ]);

        $enrollment2 = Enrollment::factory()
            ->for($student2)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $enrollment1 = Enrollment::factory()
            ->for($student1)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $result = $this->queryService()->listForFinalGrade(
            new CourseOfferingId($offering->id),
        );

        self::assertCount(2, $result);

        self::assertSame(
            [
                $enrollment1->id,
                $enrollment2->id,
            ],
            array_map(
                fn (EnrollmentForFinalGradeDTO $enrollment): int => $enrollment->enrollmentId,
                $result,
            ),
        );

        self::assertSame(
            [
                '20260001',
                '20260002',
            ],
            array_map(
                fn (EnrollmentForFinalGradeDTO $enrollment): string => $enrollment->studentNumber,
                $result,
            ),
        );
    }
}
