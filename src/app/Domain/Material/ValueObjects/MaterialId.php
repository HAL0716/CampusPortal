<?php

namespace App\Domain\Material\ValueObjects;

final readonly class MaterialId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(MaterialId $other): bool
    {
        return $this->value === $other->value;
    }
}
