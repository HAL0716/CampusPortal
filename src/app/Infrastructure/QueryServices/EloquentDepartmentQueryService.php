<?php

namespace App\Infrastructure\QueryServices;

use App\Application\Contexts\Department\DTOs\DepartmentDTO;
use App\Application\Contexts\Department\Services\DepartmentQueryService;
use App\Models\Department;

final readonly class EloquentDepartmentQueryService implements DepartmentQueryService
{
    /** @return array<DepartmentDTO> */
    public function findAll(): array
    {
        return Department::query()
            ->orderBy('name')
            ->get()
            ->map(fn ($department) => new DepartmentDTO(
                id: $department->id,
                name: $department->name,
            ))
            ->all();
    }
}
