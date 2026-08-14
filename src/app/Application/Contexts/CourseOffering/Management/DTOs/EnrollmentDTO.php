<?php

namespace App\Application\Contexts\CourseOffering\Management\DTOs;

use App\Domain\Enrollment\Enums\EnrollmentStatus;

final readonly class EnrollmentDTO
{
    public function __construct(
        public int $id,
        public string $studentNumber,
        public EnrollmentStatus $status,
    ) {}
}
