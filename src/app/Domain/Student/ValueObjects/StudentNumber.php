<?php

namespace App\Domain\Student\ValueObjects;

use App\Domain\Student\Exceptions\InvalidStudentNumberException;

final readonly class StudentNumber
{
    private const LENGTH = 6;

    private string $value;

    public function __construct(
        string $value,
    ) {
        $value = trim($value);

        if (! preg_match('/^\d{'.self::LENGTH.'}$/', $value)) {
            throw new InvalidStudentNumberException;
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(StudentNumber $other): bool
    {
        return $this->value === $other->value;
    }
}
