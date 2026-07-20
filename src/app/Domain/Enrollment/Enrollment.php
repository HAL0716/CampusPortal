<?php

namespace App\Domain\Enrollment;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\Exceptions\EnrollmentIdNotAssignedException;
use App\Domain\Student\StudentId;

final class Enrollment
{
    private function __construct(
        private ?EnrollmentId $id,
        private StudentId $studentId,
        private CourseOfferingId $courseOfferingId,
        private EnrollmentStatus $status
    ) {}

    public static function create(
        StudentId $studentId,
        CourseOfferingId $courseOfferingId,
        EnrollmentStatus $status = EnrollmentStatus::ENROLLED
    ): self {
        return new self(null, $studentId, $courseOfferingId, $status);
    }

    public static function reconstruct(
        EnrollmentId $id,
        StudentId $studentId,
        CourseOfferingId $courseOfferingId,
        EnrollmentStatus $status
    ): self {
        return new self($id, $studentId, $courseOfferingId, $status);
    }

    public function id(): ?EnrollmentId
    {
        return $this->id;
    }

    public function requireId(): EnrollmentId
    {
        if ($this->id === null) {
            throw new EnrollmentIdNotAssignedException;
        }

        return $this->id;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }

    public function courseOfferingId(): CourseOfferingId
    {
        return $this->courseOfferingId;
    }

    public function status(): EnrollmentStatus
    {
        return $this->status;
    }
}
