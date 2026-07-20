<?php

namespace App\Application\Enrollment;

final readonly class CreateEnrollmentCommand
{
    public function __construct(
        public int $courseOfferingId
    ) {}
}
