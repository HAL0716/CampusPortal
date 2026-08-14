<?php

namespace App\Domain\Role;

use App\Domain\Permission\PermissionType;
use App\Domain\Role\Enums\RoleType;

final class RolePermissions
{
    public static function permissions(RoleType $role): array
    {
        return match ($role) {

            RoleType::STUDENT => [
                PermissionType::DashboardView,
                PermissionType::CourseOfferingEnrollment,
            ],

            RoleType::TEACHER => [
                PermissionType::DashboardView,
                PermissionType::CourseOfferingManagement,
            ],

            RoleType::ADMIN => [
                PermissionType::DashboardView,
                PermissionType::CourseOfferingAdministration,
            ],

            default => [],
        };
    }
}
