<?php

namespace Tests\Support\TestHelpers;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Enums\PermissionType;

trait PermissionTestHelper
{
    use IdTestHelper;

    protected function createPermission(
        PermissionType $name = PermissionType::DashboardView,
    ): Permission {
        return Permission::create(
            name: $name,
        );
    }

    protected function reconstructPermission(
        ?int $id = null,
        PermissionType $name = PermissionType::DashboardView,
    ): Permission {
        return Permission::reconstruct(
            id: $this->permissionId($id),
            name: $name,
        );
    }
}
