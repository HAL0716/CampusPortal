<?php

namespace App\Domain\Student;

final readonly class StudentId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(StudentId $other): bool
    {
        return $this->value === $other->value;
    }
}
