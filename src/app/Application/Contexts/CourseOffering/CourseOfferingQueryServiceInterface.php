<?php

namespace App\Application\Contexts\CourseOffering;

use App\Application\Contexts\CourseOffering\Administration\CourseOfferingDTO as AdministrationDTO;
use App\Application\Contexts\CourseOffering\Enrollment\CourseOfferingDTO as EnrollmentDTO;
use App\Application\Contexts\CourseOffering\Management\CourseOfferingDTO as ManagementDTO;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
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
