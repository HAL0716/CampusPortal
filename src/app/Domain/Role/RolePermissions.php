<?php

namespace App\Domain\Role;

use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Role\Enums\RoleType;

final class RolePermissions
{
    public static function permissions(RoleType $role): array
    {
        return match ($role) {

            RoleType::STUDENT => [
                PermissionType::DashboardView,
                PermissionType::CourseOfferingView,
                PermissionType::EnrollmentManage,
                PermissionType::MaterialView,
            ],

            RoleType::TEACHER => [
                PermissionType::DashboardView,
                PermissionType::CourseOfferingView,
                PermissionType::CourseOfferingManage,
                PermissionType::FinalGradeCreate,
                PermissionType::MaterialView,
                PermissionType::MaterialCreate,
            ],

            RoleType::ADMIN => [
                PermissionType::DashboardView,
                PermissionType::CourseOfferingView,
                PermissionType::CourseOfferingManage,
                PermissionType::MaterialView,

            ],

            default => [],
        };
    }
}
