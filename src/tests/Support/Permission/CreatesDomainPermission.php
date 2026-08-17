<?php

namespace Tests\Support\Permission;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Permission\ValueObjects\PermissionId;

trait CreatesDomainPermission
{
    use PermissionTestData;

    protected function permissionIdValueObject(?int $id = null): PermissionId
    {
        return new PermissionId($id ?? $this->permissionId());
    }

    protected function permissionType(
        ?PermissionType $permission = null
    ): PermissionType {
        return $permission ?? PermissionType::DashboardView;
    }

    protected function createPermission(
        ?PermissionType $permission = null,
    ): Permission {
        return Permission::create(
            name: $this->permissionType($permission),
        );
    }

    protected function reconstructPermission(
        ?int $id = null,
        ?PermissionType $permission = null,
    ): Permission {
        return Permission::reconstruct(
            id: $this->permissionIdValueObject($id),
            name: $this->permissionType($permission),
        );
    }
}
