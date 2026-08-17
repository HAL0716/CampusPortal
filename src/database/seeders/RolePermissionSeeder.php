<?php

namespace Database\Seeders;

use App\Domain\Role\Enums\RoleType;
use App\Domain\Role\RolePermissions;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleType::cases() as $roleType) {

            $role = Role::where(
                'name',
                $roleType
            )->firstOrFail();

            $permissions = collect(RolePermissions::permissions($roleType))
                ->map(
                    fn ($permissionType) => Permission::where(
                        'name',
                        $permissionType
                    )->firstOrFail()->id
                );

            $role->permissions()->sync(
                $permissions
            );
        }
    }
}
