<?php

namespace App\Domain\Department\ValueObjects;

final readonly class DepartmentId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(DepartmentId $other): bool
    {
        return $this->value === $other->value;
    }
}
