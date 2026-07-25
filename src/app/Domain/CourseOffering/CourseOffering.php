<?php

namespace App\Domain\CourseOffering;

use App\Domain\Course\CourseId;
use App\Domain\Semester\SemesterId;
use App\Domain\Teacher\TeacherId;

final readonly class CourseOffering
{
    /**
     * @param  TeacherId[]  $teacherIds
     */
    private function __construct(
        private CourseOfferingId $id,
        private CourseId $courseId,
        private SemesterId $semesterId,
        private ?array $teacherIds,
    ) {}

    public static function reconstruct(
        CourseOfferingId $id,
        SemesterId $semesterId,
        CourseId $courseId,
        ?array $teacherIds,
    ): self {
        return new self($id, $courseId, $semesterId, $teacherIds);
    }

    public function hasTeacher(TeacherId $teacherId): bool
    {
        return collect($this->teacherIds)
            ->contains(fn (TeacherId $id) => $id->value() === $teacherId->value());
    }

    public function id(): CourseOfferingId
    {
        return $this->id;
    }

    public function courseId(): CourseId
    {
        return $this->courseId;
    }

    public function semesterId(): SemesterId
    {
        return $this->semesterId;
    }
}
