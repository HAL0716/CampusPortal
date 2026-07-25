<?php

namespace App\Application\CourseOffering\Management;

final readonly class StudentDTO
{
    public function __construct(
        public int $enrollmentId,
        public int $studentId,
        public string $studentNumber,
    ) {}
}
