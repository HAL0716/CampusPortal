<?php

namespace App\Application\Enrollment;

use App\Domain\Enrollment\EnrollmentId;

final readonly class CompleteCommand
{
    public function __construct(
        public EnrollmentId $enrollmentId
    ) {}
}
