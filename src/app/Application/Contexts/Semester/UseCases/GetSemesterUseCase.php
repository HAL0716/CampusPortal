<?php

namespace App\Application\Contexts\Semester\UseCases;

use App\Application\Contexts\Semester\DTOs\SemesterDetailDTO;
use App\Application\Contexts\Semester\Queries\GetSemesterQuery;
use App\Application\Contexts\Semester\Services\SemesterQueryService;

final readonly class GetSemesterUseCase
{
    public function __construct(
        private SemesterQueryService $queryService,
    ) {}

    public function execute(GetSemesterQuery $query): SemesterDetailDTO
    {
        return $this->queryService->getDetail($query->semesterId);
    }
}
