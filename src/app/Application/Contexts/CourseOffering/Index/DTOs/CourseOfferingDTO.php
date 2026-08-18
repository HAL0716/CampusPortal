<?php

namespace App\Application\Contexts\CourseOffering\Index\DTOs;

final readonly class CourseOfferingDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
    ) {}
}
