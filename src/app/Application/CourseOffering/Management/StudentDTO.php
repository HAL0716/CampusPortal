<?php

namespace App\Application\CourseOffering\Management;

use App\Domain\Enrollment\EnrollmentStatus;

final readonly class StudentDTO
{
    public function __construct(
        public int $id,
        public string $studentNumber,
        public EnrollmentStatus $status,
    ) {}
}
