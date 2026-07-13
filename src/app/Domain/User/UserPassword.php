<?php

namespace App\Domain\User;

use App\Domain\User\Exceptions\InvalidUserPasswordException;

final readonly class UserPassword
{
    private const ENCODING = 'UTF-8';

    private const MIN_LENGTH = 8;

    private const NO_HASH_ALGORITHM = 'unknown';

    private function __construct(
        private string $value,
        private bool $hashed
    ) {}

    public static function create(string $plain): self
    {
        if (mb_strlen($plain, self::ENCODING) < self::MIN_LENGTH) {
            throw new InvalidUserPasswordException;
        }

        return new self($plain, false);
    }

    public static function fromHash(string $hashed): self
    {
        if (password_get_info($hashed)['algoName'] === self::NO_HASH_ALGORITHM) {
            throw new InvalidUserPasswordException;
        }

        return new self($hashed, true);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isHashed(): bool
    {
        return $this->hashed;
    }
}
