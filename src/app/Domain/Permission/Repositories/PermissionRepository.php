<?php

namespace App\Domain\Permission\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\User\Entities\User;

interface PermissionRepository
{
    /**
     * @return array<Permission>
     */
    public function findByUser(User $user): array;
}
