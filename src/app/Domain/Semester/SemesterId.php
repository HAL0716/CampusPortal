<?php

namespace App\Domain\Semester;

final readonly class SemesterId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(SemesterId $other): bool
    {
        return $this->value === $other->value;
    }
}
