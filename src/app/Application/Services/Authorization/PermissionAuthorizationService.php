<?php

namespace App\Application\Services\Authorization;

use App\Domain\Permission\Enums\PermissionType;
use App\Domain\User\Entities\User;

interface PermissionAuthorizationService
{
    /**
     * @return array<string>
     */
    public function permissions(User $user): array;

    public function can(User $user, PermissionType $permission): bool;
}
