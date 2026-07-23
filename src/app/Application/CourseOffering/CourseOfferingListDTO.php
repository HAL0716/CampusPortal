<?php

namespace App\Application\CourseOffering;

use App\Domain\Enrollment\EnrollmentStatus;

final readonly class CourseOfferingListDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?EnrollmentStatus $status = null,
    ) {}
}
