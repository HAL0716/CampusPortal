<?php

namespace Tests\Support\Enrollment;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Student\ValueObjects\StudentId;
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
        EnrollmentRepository&MockInterface $enrollments,
        ?Enrollment $enrollment,
    ): void {
        $enrollments
            ->shouldReceive('findById')
            ->once()
            ->withArgs($this->idMatcher($enrollment?->requireId() ?? $this->enrollmentId()))
            ->andReturn($enrollment);
    }

    private function expectEnrollment(
        EnrollmentRepository&MockInterface $enrollments,
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
