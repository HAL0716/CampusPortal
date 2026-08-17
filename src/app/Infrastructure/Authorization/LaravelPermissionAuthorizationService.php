<?php

namespace App\Infrastructure\Authorization;

use App\Application\Services\Authorization\PermissionAuthorizationService;
use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\User\Entities\User;

final class LaravelPermissionAuthorizationService implements PermissionAuthorizationService
{
    private array $cachedPermissions = [];

    public function __construct(
        private PermissionRepository $permissions,
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
