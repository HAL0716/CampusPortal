<?php

namespace App\Infrastructure\Authorization;

use App\Application\Authorization\PermissionServiceInterface;
use App\Domain\Permission\PermissionRepositoryInterface;
use App\Domain\User\User;

final class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        private PermissionRepositoryInterface $permissions,
    ) {}

    /**
     * @return array<string>
     */
    public function permissions(User $user): array
    {
        return collect($this->permissions->findByUser($user))
            ->map(fn ($permission) => $permission->name()->value)
            ->unique()
            ->values()
            ->all();
    }
}
