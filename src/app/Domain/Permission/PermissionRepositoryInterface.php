<?php

namespace App\Domain\Permission;

use App\Domain\Permission\Entities\Permission;
use App\Domain\User\Entities\User;

interface PermissionRepositoryInterface
{
    /**
     * @return Permission[]
     */
    public function findByUser(User $user): array;
}
