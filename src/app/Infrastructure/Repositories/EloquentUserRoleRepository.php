<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Role\Enums\RoleType;
use App\Domain\Role\Exceptions\RoleNotFoundException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRoleRepository;
use App\Domain\User\ValueObjects\UserId;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;

final class EloquentUserRoleRepository implements UserRoleRepository
{
    /** @param array<RoleType> $roles */
    public function assign(UserId $userId, array $roles): void
    {
        $user = UserModel::find($userId->value());

        if ($user === null) {
            throw new UserNotFoundException;
        }

        foreach ($roles as $role) {
            $roleModel = RoleModel::where('name', $role->value)->first();

            if ($roleModel === null) {
                throw new RoleNotFoundException;
            }

            $user->roles()->syncWithoutDetaching($roleModel->id);
        }
    }
}
