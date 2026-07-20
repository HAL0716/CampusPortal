<?php

namespace App\Domain\Enrollment;

interface EnrollmentRepositoryInterface
{
    public function save(Enrollment $enrollment): Enrollment;
}
