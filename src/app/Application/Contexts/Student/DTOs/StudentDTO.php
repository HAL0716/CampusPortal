<?php

namespace App\Application\Contexts\Student\DTOs;

final readonly class StudentDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $studentNumber,
        public string $department,
    ) {}
}
