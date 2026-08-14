<?php

namespace App\Application\Authorization;

use App\Domain\Permission\Enums\PermissionType;
use App\Domain\User\Entities\User;

interface PermissionServiceInterface
{
    /**
     * @return array<string>
     */
    public function permissions(User $user): array;

    public function can(User $user, PermissionType $permission): bool;
}
