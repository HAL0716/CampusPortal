<?php

namespace App\Application\Enrollment;

final readonly class DropEnrollmentCommand
{
    public function __construct(
        public int $courseOfferingId
    ) {}
}
