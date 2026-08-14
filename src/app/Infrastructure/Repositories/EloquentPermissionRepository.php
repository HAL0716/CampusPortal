<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Models\Permission as PermissionModel;
use App\Models\User as UserModel;

final class EloquentPermissionRepository implements PermissionRepository
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
