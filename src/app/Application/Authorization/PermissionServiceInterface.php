<?php

namespace App\Application\Authorization;

use App\Domain\User\User;

interface PermissionServiceInterface
{
    /**
     * @return array<string>
     */
    public function permissions(User $user): array;
}
