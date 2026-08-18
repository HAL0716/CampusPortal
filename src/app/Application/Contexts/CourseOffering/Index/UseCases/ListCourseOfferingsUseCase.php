<?php

namespace App\Application\Contexts\CourseOffering\Index\UseCases;

use App\Application\Contexts\CourseOffering\Index\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Index\Queries\ListCourseOfferingsQuery;
use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepository $semesters,
        private CourseOfferingQueryService $queryService,
    ) {}

    /**
     * @return CourseOfferingDTO[]
     */
    public function execute(ListCourseOfferingsQuery $query): array
    {
        $semester = $this->semesters->findByDate($query->date);
        if ($semester === null) {
            throw new SemesterNotFoundException;
        }

        return $this->queryService->findBySemester($semester->requireId());
    }
}
