<?php

namespace App\Application\CourseOffering\Management;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Teacher\Exceptions\TeacherNotFoundException;
use App\Domain\Teacher\TeacherRepositoryInterface;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepositoryInterface $semesters,
        private TeacherRepositoryInterface $teachers,
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
