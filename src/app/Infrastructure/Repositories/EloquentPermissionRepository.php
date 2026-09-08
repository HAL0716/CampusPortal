<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\User\ValueObjects\UserId;
use App\Models\Permission as PermissionModel;

final class EloquentPermissionRepository implements PermissionRepository
{
    /**
     * @return array<Permission>
     */
    public function findByUserId(UserId $userId): array
    {
        return PermissionModel::query()
            ->whereHas('roles.users', fn ($query) => $query->whereKey($userId->value()))
            ->get()
            ->map(fn (PermissionModel $permission): Permission => $this->toEntity($permission))
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
