<?php

namespace App\Application\CourseOffering;

use App\Application\CourseOffering\Administration\CourseOfferingDTO as AdministrationDTO;
use App\Application\CourseOffering\Enrollment\CourseOfferingDTO as EnrollmentDTO;
use App\Application\CourseOffering\Management\CourseOfferingDTO as ManagementDTO;
use App\Domain\Semester\SemesterId;
use App\Domain\Student\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;

interface CourseOfferingQueryServiceInterface
{
    /**
     * @return array<AdministrationDTO>
     */
    public function findForAdministration(
        SemesterId $semesterId
    ): array;

    /**
     * @return array<EnrollmentDTO>
     */
    public function findForEnrollment(
        SemesterId $semesterId,
        StudentId $studentId
    ): array;

    /**
     * @return array<ManagementDTO>
     */
    public function findForManagement(
        SemesterId $semesterId,
        TeacherId $teacherId
    ): array;
}
