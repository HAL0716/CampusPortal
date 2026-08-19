<?php

namespace App\Infrastructure\QueryServices;

use App\Application\Contexts\CourseOffering\Index\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\Index\Enums\CourseOfferingStatus;
use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Application\Contexts\CourseOffering\Show\DTOs\CourseOfferingDTO as DetailDTO;
use App\Application\Contexts\CourseOffering\Show\DTOs\MaterialDTO;
use App\Application\Services\Clock\Clock;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Builder;

final class EloquentCourseOfferingQueryService implements CourseOfferingQueryService
{
    public function __construct(
        private readonly Clock $clock,
    ) {}

    /**
     * @return array<CourseOfferingDTO>
     */
    public function findBySemester(SemesterId $semesterId, StudentId|TeacherId|null $memberId = null): array
    {
        $courseOfferings = CourseOffering::query()
            ->join('courses', 'courses.id', '=', 'course_offerings.course_id')
            ->where('course_offerings.semester_id', $semesterId->value())
            ->select(['course_offerings.id', 'courses.name', 'courses.description'])
            ->get();

        $statuses = match (true) {
            $memberId instanceof StudentId => $this->findStudentStatuses($semesterId, $memberId),
            $memberId instanceof TeacherId => $this->findTeacherStatuses($memberId, $courseOfferings->pluck('id')->all()),
            default => [],
        };

        return $courseOfferings->map(
            fn (CourseOffering $courseOffering): CourseOfferingDTO => new CourseOfferingDTO(
                id: $courseOffering->id,
                name: $courseOffering->name,
                description: $courseOffering->description,
                status: $statuses[$courseOffering->id] ?? CourseOfferingStatus::NONE,
            ),
        )->all();
    }

    /**
     * @return array<int, CourseOfferingStatus>
     */
    private function findStudentStatuses(SemesterId $semesterId, StudentId $studentId): array
    {
        return Enrollment::query()
            ->whereHas('courseOffering', fn ($query) => $query->where('semester_id', $semesterId->value()))
            ->where('student_id', $studentId->value())
            ->pluck('status', 'course_offering_id')
            ->map(fn (EnrollmentStatus $status): CourseOfferingStatus => CourseOfferingStatus::from($status->value))
            ->all();
    }

    /**
     * @param  array<int, int>  $courseOfferingIds
     * @return array<int, CourseOfferingStatus>
     */
    private function findTeacherStatuses(TeacherId $teacherId, array $courseOfferingIds): array
    {
        $statuses = array_fill_keys($courseOfferingIds, CourseOfferingStatus::NOT_TEACHING);

        $teachingCourseOfferingIds = CourseOffering::query()
            ->join('course_teacher', 'course_teacher.course_id', '=', 'course_offerings.course_id')
            ->whereIn('course_offerings.id', $courseOfferingIds)
            ->where('course_teacher.teacher_id', $teacherId->value())
            ->pluck('course_offerings.id')
            ->all();

        foreach ($teachingCourseOfferingIds as $courseOfferingId) {
            $statuses[$courseOfferingId] = CourseOfferingStatus::TEACHING;
        }

        return $statuses;
    }

    public function findDetail(CourseOfferingId $id, StudentId|TeacherId|null $memberId = null): DetailDTO
    {
        $offering = CourseOffering::query()
            ->with([
                'course.teachers.user',
                'materials' => fn ($query) => $query
                    ->where(fn ($query) => $query
                        ->where('publish_date', '<=', $this->clock->now())
                        ->orWhereNull('publish_date')
                    )
                    ->orderBy('publish_date'),
            ])
            ->findOrFail($id->value());

        $status = match (true) {
            $memberId instanceof StudentId => $this->findStudentStatus($id, $memberId),
            $memberId instanceof TeacherId => $this->findTeacherStatus($id, $memberId),
            default => CourseOfferingStatus::NONE,
        };

        return new DetailDTO(
            id: $offering->id,
            name: $offering->course->name,
            description: $offering->course->description,
            status: $status,
            teachers: $offering->course->teachers->map(fn ($teacher) => $teacher->user->name)->all(),
            materials: $offering->materials->map(fn ($material) => new MaterialDTO(
                id: $material->id,
                title: $material->title,
            ))->all(),
        );
    }

    private function findStudentStatus(CourseOfferingId $courseOfferingId, StudentId $studentId): CourseOfferingStatus
    {
        $status = Enrollment::query()
            ->where('course_offering_id', $courseOfferingId->value())
            ->where('student_id', $studentId->value())
            ->value('status');

        return $status instanceof EnrollmentStatus ? CourseOfferingStatus::from($status->value) : CourseOfferingStatus::NONE;
    }

    private function findTeacherStatus(CourseOfferingId $courseOfferingId, TeacherId $teacherId): CourseOfferingStatus
    {
        $exists = CourseOffering::query()
            ->whereKey($courseOfferingId->value())
            ->whereHas('course.teachers', fn ($query) => $query->whereKey($teacherId->value()))
            ->exists();

        return $exists ? CourseOfferingStatus::TEACHING : CourseOfferingStatus::NOT_TEACHING;
    }

    private function baseQuery(SemesterId $semesterId): Builder
    {
        return CourseOffering::query()
            ->where('course_offerings.semester_id', $semesterId->value());
    }
}
