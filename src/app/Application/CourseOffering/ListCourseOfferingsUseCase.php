<?php

namespace App\Application\CourseOffering;

use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\SemesterRepositoryInterface;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\Teacher\TeacherRepositoryInterface;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private StudentRepositoryInterface $students,
        private TeacherRepositoryInterface $teachers,
        private SemesterRepositoryInterface $semesters,
        private CourseOfferingQueryServiceInterface $queryService,
    ) {}

    /**
     * @return CourseOfferingListDTO[]
     */
    public function execute(ListCourseOfferingsQuery $query): array
    {
        $semester = $this->semesters->findByDate($query->date);
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

        $teacher = $this->teachers->findByUserId($query->userId);

        if ($teacher !== null) {
            return $this->queryService->findBySemesterForTeacher(
                $semester->requireId(),
                $teacher->requireId(),
            );
        }

        return $this->queryService->findBySemester(
            $semester->requireId(),
        );
    }
}
