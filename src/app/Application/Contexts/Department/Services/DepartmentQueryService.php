<?php

namespace App\Application\Contexts\Department\Services;

use App\Application\Contexts\Department\DTOs\DepartmentDTO;

interface DepartmentQueryService
{
    /** @return array<DepartmentDTO> */
    public function findAll(): array;
}
