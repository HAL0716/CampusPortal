<?php

namespace App\Application\Contexts\Department\DTOs;

final readonly class DepartmentDTO
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
