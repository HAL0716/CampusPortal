<?php

namespace App\Application\Contexts\CourseOffering\Show\UseCases;

use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Application\Contexts\CourseOffering\Show\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Show\Queries\GetCourseOfferingQuery;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Teacher\Repositories\TeacherRepository;

final readonly class GetCourseOfferingUseCase
{
    public function __construct(
        private readonly StudentRepository $students,
        private readonly TeacherRepository $teachers,
        private CourseOfferingQueryService $queryService,
    ) {}

    public function execute(GetCourseOfferingQuery $query): CourseOfferingDTO
    {
        $student = $this->students->findByUserId($query->userId);
        if ($student !== null) {
            return $this->queryService->findDetail($query->courseOfferingId, $student->requireId());
        }

        $teacher = $this->teachers->findByUserId($query->userId);
        if ($teacher !== null) {
            return $this->queryService->findDetail($query->courseOfferingId, $teacher->requireId());
        }

        return $this->queryService->findDetail($query->courseOfferingId);
    }
}
