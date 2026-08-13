<?php

namespace App\Application\CourseOffering\Enrollment;

use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepositoryInterface $semesters,
        private StudentRepository $students,
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

        $student = $this->students->findByUserId($query->userId);
        if ($student === null) {
            throw new StudentNotFoundException;
        }

        return $this->queryService->findForEnrollment(
            $semester->requireId(),
            $student->requireId(),
        );
    }
}
