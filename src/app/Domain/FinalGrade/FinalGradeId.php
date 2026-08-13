<?php

namespace App\Domain\FinalGrade;

final readonly class FinalGradeId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(FinalGradeId $other): bool
    {
        return $this->value === $other->value;
    }
}
