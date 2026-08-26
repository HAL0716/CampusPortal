<?php

namespace App\Application\Contexts\CourseOffering\Services;

use App\Application\Contexts\CourseOffering\DTOs\CourseOfferingDetailDTO;
use App\Application\Contexts\CourseOffering\DTOs\CourseOfferingDTO;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;

interface CourseOfferingQueryService
{
    /**
     * @return array<CourseOfferingDTO>
     */
    public function findBySemester(SemesterId $semesterId, StudentId|TeacherId|null $memberId = null): array;

    public function getDetail(CourseOfferingId $id, StudentId|TeacherId|null $memberId = null): CourseOfferingDetailDTO;
}
