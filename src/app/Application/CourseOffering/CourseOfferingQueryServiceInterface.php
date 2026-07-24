<?php

namespace App\Application\CourseOffering;

use App\Domain\Semester\SemesterId;
use App\Domain\Student\StudentId;
use App\Domain\Teacher\TeacherId;

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

    /**
     * @return array<CourseOfferingListDTO>
     */
    public function findBySemesterForTeacher(SemesterId $semesterId, TeacherId $teacherId): array;
}
