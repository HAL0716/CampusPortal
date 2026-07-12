<?php

namespace App\Domain\User;

use App\Domain\User\Exceptions\InvalidUserEmailException;

final readonly class UserEmail
{
    private string $value;

    public function __construct(
        string $value
    ) {
        $value = strtolower(trim($value));

        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidUserEmailException;
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserEmail $other): bool
    {
        return $this->value === $other->value;
    }
}
