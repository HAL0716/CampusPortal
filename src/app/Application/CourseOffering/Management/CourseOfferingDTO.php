<?php

namespace App\Application\CourseOffering\Management;

final readonly class CourseOfferingDTO
{
    /**
     * @param  array<StudentDTO>  $students
     */
    public function __construct(
        public int $id,
        public string $name,
        public array $students,
    ) {}
}
