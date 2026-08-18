<?php

namespace App\Application\Contexts\CourseOffering\Services;

use App\Application\Contexts\CourseOffering\Administration\DTOs\CourseOfferingDTO as AdministrationDTO;
use App\Application\Contexts\CourseOffering\Enrollment\DTOs\CourseOfferingDTO as EnrollmentDTO;
use App\Application\Contexts\CourseOffering\Index\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Management\DTOs\CourseOfferingDTO as ManagementDTO;
use App\Application\Contexts\CourseOffering\Show\DTOs\CourseOfferingDTO as DetailDTO;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;

interface CourseOfferingQueryService
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

    /**
     * @return array<CourseOfferingDTO>
     */
    public function findBySemester(SemesterId $semesterId): array;

    public function findDetail(CourseOfferingId $id): DetailDTO;
}
