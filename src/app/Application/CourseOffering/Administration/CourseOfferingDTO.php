<?php

namespace App\Application\CourseOffering\Administration;

final readonly class CourseOfferingDTO
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
