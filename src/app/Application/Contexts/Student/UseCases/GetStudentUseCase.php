<?php

namespace App\Application\Contexts\Student\UseCases;

use App\Application\Contexts\Student\DTOs\StudentDetailDTO;
use App\Application\Contexts\Student\Queries\GetStudentQuery;
use App\Application\Contexts\Student\Services\StudentQueryService;

final readonly class GetStudentUseCase
{
    public function __construct(
        private StudentQueryService $queryService,
    ) {}

    public function execute(GetStudentQuery $query): StudentDetailDTO
    {
        return $this->queryService->getDetail($query->studentId);
    }
}
