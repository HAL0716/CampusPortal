<?php

namespace App\Application\Contexts\CourseOffering\UseCases;

use App\Application\Contexts\CourseOffering\DTOs\CourseOfferingDetailDTO;
use App\Application\Contexts\CourseOffering\Queries\GetCourseOfferingQuery;
use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Teacher\Repositories\TeacherRepository;

final readonly class GetCourseOfferingUseCase
{
    public function __construct(
        private readonly StudentRepository $students,
        private readonly TeacherRepository $teachers,
        private CourseOfferingQueryService $queryService,
    ) {}

    public function execute(GetCourseOfferingQuery $query): CourseOfferingDetailDTO
    {
        $student = $this->students->findByUserId($query->userId);
        if ($student !== null) {
            return $this->queryService->getDetail($query->courseOfferingId, $student->requireId());
        }

        $teacher = $this->teachers->findByUserId($query->userId);
        if ($teacher !== null) {
            return $this->queryService->getDetail($query->courseOfferingId, $teacher->requireId());
        }

        return $this->queryService->getDetail($query->courseOfferingId);
    }
}
