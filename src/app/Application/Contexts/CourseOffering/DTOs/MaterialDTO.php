<?php

namespace App\Application\Contexts\CourseOffering\DTOs;

final readonly class MaterialDTO
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}
}
