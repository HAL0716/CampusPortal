<?php

namespace App\Application\Contexts\FinalGrade\UseCases;

use App\Application\Contexts\Enrollment\Services\EnrollmentQueryService;
use App\Application\Contexts\FinalGrade\Queries\ListEnrollmentsQuery;
use App\Application\Exceptions\ForbiddenException;
use App\Application\Services\Authorization\CourseOfferingAuthorizationService;

final readonly class ListEnrollmentsUseCase
{
    public function __construct(
        private CourseOfferingAuthorizationService $courseOfferingAuth,
        private EnrollmentQueryService $queryService,
    ) {}

    public function execute(ListEnrollmentsQuery $query): array
    {
        if (! $this->courseOfferingAuth->canManage($query->userId, $query->courseOfferingId)) {
            throw new ForbiddenException;
        }

        return $this->queryService->listForFinalGrade($query->courseOfferingId);
    }
}
