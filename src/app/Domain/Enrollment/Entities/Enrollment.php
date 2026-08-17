<?php

namespace App\Domain\Enrollment\Entities;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Enrollment\Exceptions\EnrollmentIdNotAssignedException;
use App\Domain\Enrollment\Exceptions\InvalidEnrollmentStatusException;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Student\ValueObjects\StudentId;

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
        return match ($this->status) {
            EnrollmentStatus::ENROLLED => new self($this->id, $this->studentId, $this->courseOfferingId, EnrollmentStatus::DROPPED),
            EnrollmentStatus::DROPPED => $this,
            default => throw new InvalidEnrollmentStatusException,
        };
    }

    public function complete(): self
    {
        return match ($this->status) {
            EnrollmentStatus::ENROLLED => new self($this->id, $this->studentId, $this->courseOfferingId, EnrollmentStatus::COMPLETED),
            EnrollmentStatus::COMPLETED => $this,
            default => throw new InvalidEnrollmentStatusException,
        };
    }

    public function fail(): self
    {
        return match ($this->status) {
            EnrollmentStatus::ENROLLED => new self($this->id, $this->studentId, $this->courseOfferingId, EnrollmentStatus::FAILED),
            EnrollmentStatus::FAILED => $this,
            default => throw new InvalidEnrollmentStatusException,
        };
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
