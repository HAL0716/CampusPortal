<?php

namespace App\Application\Contexts\CourseOffering\Management;

final readonly class CourseOfferingDTO
{
    /**
     * @param  array<EnrollmentDTO>  $enrollments
     */
    public function __construct(
        public int $id,
        public string $name,
        public array $enrollments,
    ) {}
}
