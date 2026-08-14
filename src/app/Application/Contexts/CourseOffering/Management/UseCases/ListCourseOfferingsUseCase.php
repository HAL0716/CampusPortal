<?php

namespace App\Application\Contexts\CourseOffering\Management\UseCases;

use App\Application\Contexts\CourseOffering\Management\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Management\Queries\ListCourseOfferingsQuery;
use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Teacher\Exceptions\TeacherNotFoundException;
use App\Domain\Teacher\Repositories\TeacherRepository;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepository $semesters,
        private TeacherRepository $teachers,
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

        $teacher = $this->teachers->findByUserId($query->userId);
        if ($teacher === null) {
            throw new TeacherNotFoundException;
        }

        return $this->queryService->findForManagement(
            $semester->requireId(),
            $teacher->requireId(),
        );
    }
}
