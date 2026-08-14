<?php

namespace App\Infrastructure\QueryServices;

use App\Application\Contexts\CourseOffering\Administration\DTOs\CourseOfferingDTO as AdministrationDTO;
use App\Application\Contexts\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Application\Contexts\CourseOffering\Enrollment\DTOs\CourseOfferingDTO as EnrollmentDTO;
use App\Application\Contexts\CourseOffering\Management\DTOs\CourseOfferingDTO as ManagementDTO;
use App\Application\Contexts\CourseOffering\Management\DTOs\EnrollmentDTO as ManagementEnrollmentDTO;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Models\CourseOffering;
use App\Models\Enrollment;
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
        $latestEnrollments = Enrollment::query()
            ->join('course_offerings', 'course_offerings.id', '=', 'enrollments.course_offering_id')
            ->where('enrollments.student_id', $studentId->value())
            ->selectRaw('MAX(enrollments.id) as id, course_offerings.course_id')
            ->groupBy('course_offerings.course_id');

        return $this->baseQuery($semesterId)
            ->join('courses', 'courses.id', '=', 'course_offerings.course_id')
            ->leftJoinSub($latestEnrollments, 'latest_enrollments', function ($join) {
                $join->on('latest_enrollments.course_id', '=', 'course_offerings.course_id');
            })
            ->leftJoin('enrollments', 'enrollments.id', '=', 'latest_enrollments.id')
            ->select('course_offerings.id', 'course_offerings.course_id', 'courses.name', 'enrollments.status')
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
                enrollments: $offering->enrollments
                    ->sortBy(fn ($enrollment) => $enrollment->student->student_number)
                    ->map(fn ($enrollment) => new ManagementEnrollmentDTO(
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
