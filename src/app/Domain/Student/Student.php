<?php

namespace App\Domain\Student;

use App\Domain\Student\Exceptions\StudentIdNotAssignedException;

final readonly class Student
{
    public function __construct(
        private ?StudentId $id,
    ) {}

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
}
