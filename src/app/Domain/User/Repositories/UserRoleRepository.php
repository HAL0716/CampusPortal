<?php

namespace App\Domain\User\Repositories;

use App\Domain\Role\Enums\RoleType;
use App\Domain\User\ValueObjects\UserId;

interface UserRoleRepository
{
    /** @param array<RoleType> $roles */
    public function assign(UserId $userId, array $roles): void;
}
