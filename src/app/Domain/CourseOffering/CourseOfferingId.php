<?php

namespace App\Domain\CourseOffering;

final readonly class CourseOfferingId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(CourseOfferingId $other): bool
    {
        return $this->value === $other->value;
    }
}
