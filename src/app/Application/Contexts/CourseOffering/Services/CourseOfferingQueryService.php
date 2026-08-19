<?php

namespace App\Application\Contexts\CourseOffering\Services;

use App\Application\Contexts\CourseOffering\Index\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Show\DTOs\CourseOfferingDTO as DetailDTO;
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

    public function findDetail(CourseOfferingId $id, StudentId|TeacherId|null $memberId = null): DetailDTO;
}
