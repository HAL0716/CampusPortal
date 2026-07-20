<?php

namespace App\Application\Authorization;

use App\Domain\Permission\PermissionType;
use App\Domain\User\User;

interface PermissionServiceInterface
{
    /**
     * @return array<string>
     */
    public function permissions(User $user): array;

    public function can(User $user, PermissionType $permission): bool;
}
