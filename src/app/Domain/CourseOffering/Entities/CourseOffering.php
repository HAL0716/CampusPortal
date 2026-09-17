<?php

namespace App\Domain\CourseOffering\Entities;

use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\CourseOffering\Exceptions\CourseOfferingIdNotAssignedException;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Teacher\ValueObjects\TeacherId;

final readonly class CourseOffering
{
    /**
     * @param  array<TeacherId>  $teacherIds
     */
    private function __construct(
        private ?CourseOfferingId $id,
        private CourseId $courseId,
        private SemesterId $semesterId,
        private array $teacherIds,
    ) {}

    public static function create(
        CourseId $courseId,
        SemesterId $semesterId,
    ): self {
        return new self(null, $courseId, $semesterId, []);
    }

    public static function reconstruct(
        CourseOfferingId $id,
        SemesterId $semesterId,
        CourseId $courseId,
        array $teacherIds = [],
    ): self {
        return new self($id, $courseId, $semesterId, $teacherIds);
    }

    public function hasTeacher(TeacherId $teacherId): bool
    {
        return collect($this->teacherIds)
            ->contains(fn (TeacherId $id) => $id->value() === $teacherId->value());
    }

    public function id(): ?CourseOfferingId
    {
        return $this->id;
    }

    public function requireId(): CourseOfferingId
    {
        if ($this->id === null) {
            throw new CourseOfferingIdNotAssignedException('CourseOffering ID is not assigned.');
        }

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

    /**
     * @return array<TeacherId>
     */
    public function teacherIds(): array
    {
        return $this->teacherIds;
    }
}
