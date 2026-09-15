<?php

namespace App\Application\Contexts\Semester\UseCases;

use App\Application\Contexts\Semester\DTOs\SemesterDTO;
use App\Application\Contexts\Semester\Services\SemesterQueryService;

final readonly class GetLatestSemesterUseCase
{
    public function __construct(
        private SemesterQueryService $queryService,
    ) {}

    public function execute(): SemesterDTO
    {
        return $this->queryService->getLatest();
    }
}
