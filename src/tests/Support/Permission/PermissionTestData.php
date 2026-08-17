<?php

namespace Tests\Support\Permission;

use App\Domain\Permission\Enums\PermissionType;

trait PermissionTestData
{
    protected function permissionId(): int
    {
        return 1;
    }

    protected function permissionName(): PermissionType
    {
        return PermissionType::DashboardView;
    }
}
