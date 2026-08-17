<?php

namespace App\Application\Contexts\CourseOffering\Show\UseCases;

use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Application\Contexts\CourseOffering\Show\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Show\Queries\GetCourseOfferingQuery;

final readonly class GetCourseOfferingUseCase
{
    public function __construct(
        private CourseOfferingQueryService $queryService,
    ) {}

    public function execute(GetCourseOfferingQuery $query): CourseOfferingDTO
    {
        return $this->queryService->findDetail(
            $query->courseOfferingId,
        );
    }
}
