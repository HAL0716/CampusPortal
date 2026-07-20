<?php

namespace App\Domain\Student;

use App\Domain\Student\Exceptions\StudentIdNotAssignedException;
use App\Domain\User\UserId;

final class Student
{
    private function __construct(
        private ?StudentId $id,
        private UserId $userId,
    ) {}

    public static function create(UserId $userId): self
    {
        return new self(null, $userId);
    }

    public static function reconstruct(StudentId $id, UserId $userId): self
    {
        return new self($id, $userId);
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
}
