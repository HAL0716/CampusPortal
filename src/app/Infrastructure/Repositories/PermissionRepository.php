<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Permission\Permission;
use App\Domain\Permission\PermissionId;
use App\Domain\Permission\PermissionRepositoryInterface;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\User;
use App\Models\Permission as PermissionModel;
use App\Models\User as UserModel;

final class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * @return Permission[]
     */
    public function findByUser(User $user): array
    {
        $model = UserModel::find(
            $user->requireId()->value()
        );

        if ($model === null) {
            throw new UserNotFoundException;
        }

        return $model
            ->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->map(
                fn (PermissionModel $permission): Permission => $this->toEntity($permission)
            )
            ->values()
            ->all();
    }

    private function toEntity(
        PermissionModel $model
    ): Permission {
        return Permission::reconstruct(
            new PermissionId($model->id),
            $model->name
        );
    }
}
