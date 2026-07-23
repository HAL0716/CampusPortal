<?php

namespace App\Application\CourseOffering;

use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Student\StudentRepositoryInterface;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private StudentRepositoryInterface $students,
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

        $student = $this->students->findByUserId($query->userId);

        if ($student !== null) {
            return $this->queryService->findBySemesterForStudent(
                $semester->requireId(),
                $student->requireId(),
            );
        }

        return $this->queryService->findBySemester(
            $semester->requireId(),
        );
    }
}
