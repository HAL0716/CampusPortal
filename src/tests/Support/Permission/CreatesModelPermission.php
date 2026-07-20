<?php

namespace Tests\Support\Permission;

use App\Domain\Permission\PermissionType;
use App\Domain\Role\RoleType;
use App\Models\Permission as PermissionModel;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;

trait CreatesModelPermission
{
    protected function createPermission(
        UserModel $user,
        PermissionType $permission,
        RoleType $role = RoleType::TEST
    ): void {
        $role = RoleModel::firstOrCreate([
            'name' => $role,
        ]);

        $permission = PermissionModel::firstOrCreate([
            'name' => $permission,
        ]);

        $role->permissions()
            ->syncWithoutDetaching([
                $permission->id,
            ]);

        $user->roles()
            ->syncWithoutDetaching([
                $role->id,
            ]);
    }
}
