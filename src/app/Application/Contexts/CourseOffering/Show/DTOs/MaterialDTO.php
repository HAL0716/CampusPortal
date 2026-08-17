<?php

namespace App\Application\Contexts\CourseOffering\Show\DTOs;

final readonly class MaterialDTO
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}
}
