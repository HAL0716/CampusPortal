<?php

namespace App\Domain\Permission;

use App\Domain\Permission\Exceptions\PermissionIdNotAssignedException;

final class Permission
{
    private function __construct(
        private ?PermissionId $id,
        private PermissionType $name,
    ) {}

    public static function create(PermissionType $name): self
    {
        return new self(null, $name);
    }

    public static function reconstruct(PermissionId $id, PermissionType $name): self
    {
        return new self($id, $name);
    }

    public function id(): ?PermissionId
    {
        return $this->id;
    }

    public function requireId(): PermissionId
    {
        if ($this->id === null) {
            throw new PermissionIdNotAssignedException;
        }

        return $this->id;
    }

    public function name(): PermissionType
    {
        return $this->name;
    }
}
