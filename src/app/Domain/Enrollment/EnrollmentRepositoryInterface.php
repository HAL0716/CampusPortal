<?php

namespace App\Domain\Enrollment;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Student\StudentId;

interface EnrollmentRepositoryInterface
{
    public function save(Enrollment $enrollment): Enrollment;

    public function find(
        StudentId $studentId,
        CourseOfferingId $courseOfferingId,
    ): ?Enrollment;
}
