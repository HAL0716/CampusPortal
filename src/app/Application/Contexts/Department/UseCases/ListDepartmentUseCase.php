<?php

namespace App\Application\Contexts\Department\UseCases;

use App\Application\Contexts\Department\DTOs\DepartmentDTO;
use App\Application\Contexts\Department\Services\DepartmentQueryService;

final readonly class ListDepartmentUseCase
{
    public function __construct(
        private DepartmentQueryService $queryService,
    ) {}

    /** @return array<DepartmentDTO> */
    public function execute(): array
    {
        return $this->queryService->findAll();
    }
}
