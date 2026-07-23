<?php

namespace App\Application\CourseOffering;

use App\Domain\Semester\SemesterId;
use App\Domain\Student\StudentId;

interface CourseOfferingQueryServiceInterface
{
    /**
     * @return array<CourseOfferingListDTO>
     */
    public function findBySemester(SemesterId $semesterId): array;

    /**
     * @return array<CourseOfferingListDTO>
     */
    public function findBySemesterForStudent(SemesterId $semesterId, StudentId $studentId): array;
}
