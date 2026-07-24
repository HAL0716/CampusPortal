<?php

namespace App\Application\CourseOffering\Administration;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepositoryInterface $semesters,
        private CourseOfferingQueryServiceInterface $queryService,
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

        return $this->queryService->findForAdministration(
            $semester->requireId(),
        );
    }
}
