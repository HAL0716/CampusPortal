<?php

namespace App\Domain\Enrollment;

final readonly class EnrollmentId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(EnrollmentId $other): bool
    {
        return $this->value === $other->value;
    }
}
