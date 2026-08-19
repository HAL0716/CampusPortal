<?php

namespace App\Infrastructure\QueryServices;

use App\Application\Contexts\Enrollment\Services\EnrollmentQueryService;
use App\Application\Contexts\FinalGrade\DTOs\EnrollmentForFinalGradeDTO;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Models\Enrollment as EnrollmentModel;

final class EloquentEnrollmentQueryService implements EnrollmentQueryService
{
    /** @return array<EnrollmentForFinalGradeDTO> */
    public function listForFinalGrade(CourseOfferingId $courseOfferingId): array
    {
        return EnrollmentModel::query()
            ->join('students', 'students.id', '=', 'enrollments.student_id')
            ->leftJoin('final_grades', 'final_grades.enrollment_id', '=', 'enrollments.id')
            ->where('enrollments.course_offering_id', $courseOfferingId->value())
            ->where('enrollments.status', '!=', EnrollmentStatus::DROPPED)
            ->orderBy('students.student_number')
            ->get(['enrollments.id', 'students.student_number', 'final_grades.grade'])
            ->map(fn ($enrollment) => new EnrollmentForFinalGradeDTO(
                enrollmentId: $enrollment->id,
                studentNumber: $enrollment->student_number,
                finalGrade: $enrollment->grade !== null ? FinalGradeType::from($enrollment->grade) : null,
            ))
            ->all();
    }
}
