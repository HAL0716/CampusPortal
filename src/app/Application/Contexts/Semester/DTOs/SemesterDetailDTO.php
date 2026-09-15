<?php

namespace App\Application\Contexts\Semester\DTOs;

final readonly class SemesterDetailDTO
{
    /** @var array<CourseOfferingDTO> */
    public function __construct(
        public int $id,
        public string $academicYear,
        public string $term,
        public string $startDate,
        public string $endDate,
        public array $courseOfferings,
    ) {}
}
