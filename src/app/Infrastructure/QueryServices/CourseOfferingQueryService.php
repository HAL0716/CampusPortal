<?php

namespace App\Infrastructure\QueryServices;

use App\Application\CourseOffering\CourseOfferingListDTO;
use App\Application\CourseOffering\CourseOfferingQueryServiceInterface;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Semester\SemesterId;
use App\Domain\Student\StudentId;
use App\Domain\Teacher\TeacherId;
use App\Models\CourseOffering;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class CourseOfferingQueryService implements CourseOfferingQueryServiceInterface
{
    public function findBySemester(
        SemesterId $semesterId,
    ): array {
        return $this->query($semesterId)
            ->get()
            ->map(fn ($offering) => $this->toDto($offering))
            ->all();
    }

    public function findBySemesterForStudent(
        SemesterId $semesterId,
        StudentId $studentId,
    ): array {
        return $this->query($semesterId, studentId: $studentId)
            ->get()
            ->map(fn ($offering) => $this->toDto($offering))
            ->all();
    }

    public function findBySemesterForTeacher(
        SemesterId $semesterId,
        TeacherId $teacherId,
    ): array {
        return $this->query($semesterId, teacherId: $teacherId)
            ->get()
            ->map(fn ($offering) => $this->toDto($offering))
            ->all();
    }

    private function query(
        SemesterId $semesterId,
        ?StudentId $studentId = null,
        ?TeacherId $teacherId = null,
    ): Builder {
        $query = CourseOffering::query()
            ->where('course_offerings.semester_id', $semesterId->value())
            ->join('courses', 'courses.id', '=', 'course_offerings.course_id')
            ->select('course_offerings.id', 'courses.name');

        if ($teacherId !== null) {
            $query
                ->join(
                    'course_teacher',
                    'course_teacher.course_id',
                    '=',
                    'courses.id'
                )
                ->where(
                    'course_teacher.teacher_id',
                    $teacherId->value()
                );
        }

        if ($studentId === null) {
            return $query->addSelect(
                DB::raw('NULL as enrollment_status')
            );
        }

        return $query
            ->leftJoin('enrollments', function ($join) use ($studentId) {
                $join->on(
                    'enrollments.course_offering_id',
                    '=',
                    'course_offerings.id'
                )->where(
                    'enrollments.student_id',
                    $studentId->value()
                );
            })
            ->addSelect('enrollments.status as enrollment_status');
    }

    private function toDto(object $offering): CourseOfferingListDTO
    {
        return new CourseOfferingListDTO(
            id: $offering->id,
            name: $offering->name,
            status: $offering->enrollment_status !== null
                ? EnrollmentStatus::from($offering->enrollment_status)
                : null,
        );
    }
}
