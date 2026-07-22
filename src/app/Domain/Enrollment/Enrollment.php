<?php

namespace App\Domain\Enrollment;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Student\StudentId;

final class Enrollment
{
    private function __construct(
        private ?EnrollmentId $id,
        private StudentId $studentId,
        private CourseOfferingId $courseOfferingId,
        private EnrollmentStatus $status
    ) {}

    public static function create(StudentId $studentId, CourseOfferingId $courseOfferingId): self
    {
        return new self(null, $studentId, $courseOfferingId, EnrollmentStatus::ENROLLED);
    }
}
