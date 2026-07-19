<?php

namespace App\Domain\Role;

use App\Domain\Permission\PermissionType;

final class RolePermissionMap
{
    public static function permissions(RoleType $role): array
    {
        return match ($role) {

            RoleType::STUDENT => [
            ],

            RoleType::TEACHER => [
            ],

            RoleType::ADMIN => [
                PermissionType::UserView,
            ],

        };
    }
}
