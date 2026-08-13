<?php

namespace App\Domain\Teacher\Entities;

use App\Domain\Teacher\Exceptions\TeacherIdNotAssignedException;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Domain\User\ValueObjects\UserId;

final readonly class Teacher
{
    public function __construct(
        private ?TeacherId $id,
        private UserId $userId
    ) {}

    public static function create(UserId $userId): self
    {
        return new self(null, $userId);
    }

    public static function reconstruct(TeacherId $id, UserId $userId): self
    {
        return new self($id, $userId);
    }

    public function id(): ?TeacherId
    {
        return $this->id;
    }

    public function requireId(): TeacherId
    {
        if ($this->id === null) {
            throw new TeacherIdNotAssignedException;
        }

        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
