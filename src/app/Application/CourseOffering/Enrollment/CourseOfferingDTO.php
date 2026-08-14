<?php

namespace App\Application\CourseOffering\Enrollment;

use App\Domain\Enrollment\Enums\EnrollmentStatus;

final readonly class CourseOfferingDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?EnrollmentStatus $status = null,
    ) {}
}
