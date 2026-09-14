<?php

namespace App\Domain\Student\Entities;

use App\Domain\Department\ValueObjects\DepartmentId;
use App\Domain\Student\Enums\StudentStatus;
use App\Domain\Student\Exceptions\InvalidStatusTransition;
use App\Domain\Student\Exceptions\StudentIdNotAssignedException;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Student\ValueObjects\StudentNumber;
use App\Domain\User\ValueObjects\UserId;

final readonly class Student
{
    public function __construct(
        private ?StudentId $id,
        private UserId $userId,
        private DepartmentId $departmentId,
        private StudentNumber $studentNumber,
        private StudentStatus $status,
    ) {}

    public static function create(UserId $userId, DepartmentId $departmentId, StudentNumber $studentNumber): self
    {
        return new self(null, $userId, $departmentId, $studentNumber, StudentStatus::ACTIVE);
    }

    public static function reconstruct(StudentId $id, UserId $userId, DepartmentId $departmentId, StudentNumber $studentNumber, StudentStatus $status): self
    {
        return new self($id, $userId, $departmentId, $studentNumber, $status);
    }

    public function transitionTo(StudentStatus $status): self
    {
        if (! $this->status->canTransitionTo($status)) {
            throw new InvalidStatusTransition($this->status, $status);
        }

        return new self($this->id, $this->userId, $this->departmentId, $this->studentNumber, $status);
    }

    public function id(): ?StudentId
    {
        return $this->id;
    }

    public function requireId(): StudentId
    {
        if ($this->id === null) {
            throw new StudentIdNotAssignedException;
        }

        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function departmentId(): DepartmentId
    {
        return $this->departmentId;
    }

    public function studentNumber(): StudentNumber
    {
        return $this->studentNumber;
    }

    public function status(): StudentStatus
    {
        return $this->status;
    }
}
