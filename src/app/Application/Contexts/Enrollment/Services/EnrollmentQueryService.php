<?php

namespace App\Application\Contexts\Enrollment\Services;

use App\Application\Contexts\FinalGrade\DTOs\EnrollmentForFinalGradeDTO;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;

interface EnrollmentQueryService
{
    /** @return array<EnrollmentForFinalGradeDTO> */
    public function listForFinalGrade(CourseOfferingId $courseOfferingId): array;
}
