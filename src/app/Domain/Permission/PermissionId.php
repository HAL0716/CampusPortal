<?php

namespace App\Domain\Permission;

final readonly class PermissionId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(PermissionId $other): bool
    {
        return $this->value === $other->value;
    }
}
