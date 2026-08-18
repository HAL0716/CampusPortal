<?php

namespace App\Application\Contexts\CourseOffering\Index\UseCases;

use App\Application\Contexts\CourseOffering\Index\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Index\Queries\ListCourseOfferingsQuery;
use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Teacher\Repositories\TeacherRepository;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepository $semesters,
        private readonly StudentRepository $students,
        private readonly TeacherRepository $teachers,
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

        $student = $this->students->findByUserId($query->userId);
        if ($student !== null) {
            return $this->queryService->findBySemester($semester->requireId(), $student->requireId());
        }

        $teacher = $this->teachers->findByUserId($query->userId);
        if ($teacher !== null) {
            return $this->queryService->findBySemester($semester->requireId(), $teacher->requireId());
        }

        return $this->queryService->findBySemester($semester->requireId());
    }
}
