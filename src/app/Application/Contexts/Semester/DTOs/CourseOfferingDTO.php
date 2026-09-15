<?php

namespace App\Application\Contexts\Semester\DTOs;

final readonly class CourseOfferingDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
    ) {}
}
