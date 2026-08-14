<?php

namespace App\Application\Contexts\CourseOffering\Enrollment;

use App\Domain\Enrollment\Enums\EnrollmentStatus;

final readonly class CourseOfferingDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?EnrollmentStatus $status = null,
    ) {}
}
