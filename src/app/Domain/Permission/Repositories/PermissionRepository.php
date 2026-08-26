<?php

namespace App\Domain\Permission\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\User\ValueObjects\UserId;

interface PermissionRepository
{
    /**
     * @return array<Permission>
     */
    public function findByUserId(UserId $userId): array;
}
