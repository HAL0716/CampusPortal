<?php

namespace App\Domain\User;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function findById(UserId $id): ?User;

    public function findByEmail(UserEmail $email): ?User;
}
