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
    ) {}

    public static function create(
        CourseId $courseId,
        SemesterId $semesterId,
    ): self {
        return new self(null, $courseId, $semesterId);
    }

    public static function reconstruct(
        CourseOfferingId $id,
        SemesterId $semesterId,
        CourseId $courseId,
    ): self {
        return new self($id, $courseId, $semesterId);
    }

    public function id(): ?CourseOfferingId
    {
        return $this->id;
    }

    public function requireId(): CourseOfferingId
    {
        if ($this->id === null) {
            throw new CourseOfferingIdNotAssignedException;
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
}
