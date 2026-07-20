<?php

namespace App\Infrastructure\Authorization;

use App\Application\Authorization\PermissionServiceInterface;
use App\Domain\Permission\PermissionRepositoryInterface;
use App\Domain\Permission\PermissionType;
use App\Domain\User\User;

final class PermissionService implements PermissionServiceInterface
{
    private array $cachedPermissions = [];

    public function __construct(
        private PermissionRepositoryInterface $permissions,
    ) {}

    /**
     * @return array<string>
     */
    public function permissions(User $user): array
    {
        $userId = $user->requireId()->value();

        if (isset($this->cachedPermissions[$userId])) {
            return $this->cachedPermissions[$userId];
        }

        $permissions = collect($this->permissions->findByUser($user))
            ->map(fn ($permission) => $permission->name()->value)
            ->unique()
            ->values()
            ->all();

        $this->cachedPermissions[$userId] = $permissions;

        return $permissions;
    }

    public function can(
        User $user,
        PermissionType $permission
    ): bool {
        return in_array($permission->value, $this->permissions($user), true);
    }
}
