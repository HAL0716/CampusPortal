<?php

namespace App\Domain\Enrollment;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\Exceptions\EnrollmentIdNotAssignedException;
use App\Domain\Enrollment\Exceptions\InvalidEnrollmentStatusException;
use App\Domain\Student\StudentId;

final readonly class Enrollment
{
    private function __construct(
        private ?EnrollmentId $id,
        private StudentId $studentId,
        private CourseOfferingId $courseOfferingId,
        private EnrollmentStatus $status
    ) {}

    public static function create(StudentId $studentId, CourseOfferingId $courseOfferingId): self
    {
        return new self(null, $studentId, $courseOfferingId, EnrollmentStatus::ENROLLED);
    }

    public static function reconstruct(
        EnrollmentId $id,
        StudentId $studentId,
        CourseOfferingId $courseOfferingId,
        EnrollmentStatus $status
    ): self {
        return new self($id, $studentId, $courseOfferingId, $status);
    }

    public function enroll(): self
    {
        return match ($this->status) {
            EnrollmentStatus::ENROLLED => $this,
            EnrollmentStatus::DROPPED => new self($this->id, $this->studentId, $this->courseOfferingId, EnrollmentStatus::ENROLLED),
            default => throw new InvalidEnrollmentStatusException,
        };
    }

    public function drop(): self
    {
        return new self($this->id, $this->studentId, $this->courseOfferingId, EnrollmentStatus::DROPPED);
    }

    public function complete(): self
    {
        if ($this->status !== EnrollmentStatus::ENROLLED) {
            throw new InvalidEnrollmentStatusException;
        }

        return new self($this->id, $this->studentId, $this->courseOfferingId, EnrollmentStatus::COMPLETED);
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
