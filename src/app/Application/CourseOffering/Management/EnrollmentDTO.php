<?php

namespace App\Application\CourseOffering\Management;

use App\Domain\Enrollment\Enums\EnrollmentStatus;

final readonly class EnrollmentDTO
{
    public function __construct(
        public int $id,
        public string $studentNumber,
        public EnrollmentStatus $status,
    ) {}
}
