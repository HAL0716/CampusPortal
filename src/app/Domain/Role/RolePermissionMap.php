<?php

namespace App\Domain\Role;

use App\Domain\Permission\PermissionType;

final class RolePermissionMap
{
    public static function permissions(RoleType $role): array
    {
        return match ($role) {

            RoleType::STUDENT => [
                PermissionType::DashboardView,
            ],

            RoleType::TEACHER => [
                PermissionType::DashboardView,
            ],

            RoleType::ADMIN => [
                PermissionType::DashboardView,
                PermissionType::UserView,
            ],

            default => [],
        };
    }
}
