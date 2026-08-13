<?php

namespace Tests\Support\Enrollment;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Student\StudentId;
use Mockery\MockInterface;
use Tests\Support\Id\IdTestHelper;
use Tests\Support\Matchers\UseMatcher;

trait EnrollmentTestHelper
{
    use IdTestHelper;
    use UseMatcher;

    private function enrollment(
        ?int $id = null,
        ?int $studentId = null,
        ?int $courseOfferingId = null,
        ?EnrollmentStatus $status = null,
    ): Enrollment {
        return Enrollment::reconstruct(
            id: $this->enrollmentId($id),
            studentId: $this->studentId($studentId),
            courseOfferingId: $this->courseOfferingId($courseOfferingId),
            status: $status ?? EnrollmentStatus::ENROLLED,
        );
    }

    private function expectEnrollmentById(
        EnrollmentRepositoryInterface&MockInterface $enrollments,
        ?Enrollment $enrollment,
    ): void {
        $enrollments
            ->shouldReceive('findById')
            ->once()
            ->withArgs($this->idMatcher($enrollment?->requireId() ?? $this->enrollmentId()))
            ->andReturn($enrollment);
    }

    private function expectEnrollment(
        EnrollmentRepositoryInterface&MockInterface $enrollments,
        ?Enrollment $enrollment,
        StudentId $studentId,
        CourseOfferingId $courseOfferingId,
    ): void {
        $enrollments
            ->shouldReceive('find')
            ->once()
            ->withArgs($this->idsMatcher($studentId, $courseOfferingId))
            ->andReturn($enrollment);
    }
}
