<?php

namespace App\Application\Contexts\CourseOffering\Administration;

use App\Application\Contexts\CourseOffering\Administration\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Administration\Queries\ListCourseOfferingsQuery;
use App\Application\Contexts\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepository $semesters,
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
