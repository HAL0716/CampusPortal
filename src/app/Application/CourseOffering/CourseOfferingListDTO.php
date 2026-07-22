<?php

namespace App\Application\CourseOffering;

final readonly class CourseOfferingListDTO
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
