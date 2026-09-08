<?php

namespace App\Application\Contexts\Student\UseCases;

use App\Application\Contexts\Student\DTOs\StudentDTO;
use App\Application\Contexts\Student\Services\StudentQueryService;

final readonly class ListStudentUseCase
{
    public function __construct(
        private StudentQueryService $queryService,
    ) {}

    /** @return array<StudentDTO> */
    public function execute(): array
    {
        return $this->queryService->findAll();
    }
}
