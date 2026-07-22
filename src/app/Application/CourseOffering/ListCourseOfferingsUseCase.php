<?php

namespace App\Application\CourseOffering;

use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepositoryInterface $semesters,
        private CourseOfferingQueryServiceInterface $queryService,
    ) {}

    /**
     * @return CourseOfferingListDTO[]
     */
    public function execute(ListCourseOfferingsQuery $query): array
    {
        $semester = $this->semesters->find(
            $query->academicYear,
            $query->term,
        );
        if ($semester === null) {
            throw new SemesterNotFoundException;
        }

        return $this->queryService->findBySemesterId($semester->requireId());
    }
}
