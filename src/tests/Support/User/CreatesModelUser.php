<?php

namespace Tests\Support\User;

use App\Domain\Permission\PermissionType;
use App\Domain\Role\RoleType;
use App\Models\Permission as PermissionModel;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

trait CreatesModelUser
{
    use UserTestData;

    protected function createUser(
        ?string $email = null,
        ?string $password = null,
        ?string $name = null,
    ): UserModel {
        return UserModel::factory()->create([
            'name' => $name ?? $this->userName(),
            'email' => $email ?? $this->userEmail(),
            'password' => Hash::make($password ?? $this->userPassword()),
        ]);
    }

    public function givePermission(
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
            ->syncWithoutDetaching([$permission->id]);

        $user->roles()
            ->syncWithoutDetaching([$role->id]);
    }

    protected function actingAsWithPermission(
        UserModel $user,
        PermissionType $permission,
        RoleType $role = RoleType::TEST
    ): UserModel {
        $this->givePermission(
            $user,
            $permission,
            $role
        );

        $this->actingAs($user);

        return $user;
    }
}
