<?php

namespace App\Application\Contexts\CourseOffering\Enrollment\UseCases;

use App\Application\Contexts\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Contexts\CourseOffering\Enrollment\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Enrollment\Queries\ListCourseOfferingsQuery;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\Student\Repositories\StudentRepository;

final class ListCourseOfferingsUseCase
{
    public function __construct(
        private SemesterRepository $semesters,
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
