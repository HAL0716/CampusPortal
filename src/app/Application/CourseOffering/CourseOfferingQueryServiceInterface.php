<?php

namespace App\Application\CourseOffering;

use App\Domain\Semester\SemesterId;

interface CourseOfferingQueryServiceInterface
{
    /**
     * @return array<CourseOfferingListDTO>
     */
    public function findBySemesterId(SemesterId $semesterId): array;
}
