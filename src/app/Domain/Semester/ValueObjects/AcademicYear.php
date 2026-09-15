<?php

namespace App\Domain\Semester\ValueObjects;

use App\Domain\Semester\Exceptions\InvalidAcademicYear;

final readonly class AcademicYear
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (! preg_match('/^\d{4}$/', $value)) {
            throw new InvalidAcademicYear($value);
        }

        $this->value = $value;
    }

    public function next(): AcademicYear
    {
        return new AcademicYear(
            (string) ((int) $this->value + 1)
        );
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(AcademicYear $other): bool
    {
        return $this->value === $other->value;
    }
}
