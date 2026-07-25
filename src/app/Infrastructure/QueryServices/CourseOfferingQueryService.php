<?php

namespace App\Infrastructure\QueryServices;

use App\Application\CourseOffering\Administration\CourseOfferingDTO as AdministrationDTO;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\CourseOffering\Enrollment\CourseOfferingDTO as EnrollmentDTO;
use App\Application\CourseOffering\Management\CourseOfferingDTO as ManagementDTO;
use App\Application\CourseOffering\Management\StudentDTO;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Semester\SemesterId;
use App\Domain\Student\StudentId;
use App\Domain\Teacher\TeacherId;
use App\Models\CourseOffering;
use Illuminate\Database\Eloquent\Builder;

final class CourseOfferingQueryService implements CourseOfferingQueryServiceInterface
{
    public function findForAdministration(SemesterId $semesterId): array
    {
        return $this->baseQuery($semesterId)
            ->join('courses', 'courses.id', '=', 'course_offerings.course_id')
            ->select(
                'course_offerings.id',
                'courses.name',
            )
            ->get()
            ->map(fn ($offering) => new AdministrationDTO(
                id: $offering->id,
                name: $offering->name,
            ))
            ->all();
    }

    public function findForEnrollment(SemesterId $semesterId, StudentId $studentId): array
    {
        return $this->baseQuery($semesterId)
            ->join('courses', 'courses.id', '=', 'course_offerings.course_id')
            ->leftJoin('enrollments', function ($join) use ($studentId) {
                $join->on('enrollments.course_offering_id', '=', 'course_offerings.id')
                    ->where('enrollments.student_id', $studentId->value());
            })
            ->select(
                'course_offerings.id',
                'courses.name',
                'enrollments.status',
            )
            ->get()
            ->map(fn ($offering) => new EnrollmentDTO(
                id: $offering->id,
                name: $offering->name,
                status: $offering->status ? EnrollmentStatus::from($offering->status) : null,
            ))
            ->all();
    }

    public function findForManagement(SemesterId $semesterId, TeacherId $teacherId): array
    {
        return $this->baseQuery($semesterId)
            ->whereHas('course.teachers', function ($query) use ($teacherId) {
                $query->where('teachers.id', $teacherId->value());
            })
            ->with([
                'course',
                'enrollments.student',
            ])
            ->get()
            ->map(fn ($offering) => new ManagementDTO(
                id: $offering->id,
                name: $offering->course->name,
                students: $offering->enrollments
                    ->sortBy(fn ($enrollment) => $enrollment->student->student_number)
                    ->map(fn ($enrollment) => new StudentDTO(
                        id: $enrollment->id,
                        studentNumber: $enrollment->student->student_number,
                        status: $enrollment->status,
                    ))
                    ->values()
                    ->all(),
            ))
            ->all();
    }

    private function baseQuery(SemesterId $semesterId): Builder
    {
        return CourseOffering::query()
            ->where('course_offerings.semester_id', $semesterId->value());
    }
}
