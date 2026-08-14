<?php

namespace App\Domain\Course\ValueObjects;

final readonly class CourseId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(CourseId $other): bool
    {
        return $this->value === $other->value;
    }
}
