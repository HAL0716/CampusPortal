<?php

namespace App\Domain\Permission;

use App\Domain\User\User;

interface PermissionRepositoryInterface
{
    /**
     * @return Permission[]
     */
    public function findByUser(User $user): array;
}
