<?php

namespace App\Application\Contexts\Semester\UseCases;

use App\Application\Contexts\Semester\DTOs\SemesterDTO;
use App\Application\Contexts\Semester\Services\SemesterQueryService;

final readonly class ListSemesterUseCase
{
    public function __construct(
        private SemesterQueryService $queryService,
    ) {}

    /** @return array<SemesterDTO> */
    public function execute(): array
    {
        return $this->queryService->findAll();
    }
}
