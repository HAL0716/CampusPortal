<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;

interface UserRepository
{
    public function save(User $user): User;

    public function findById(UserId $id): ?User;

    public function findByEmail(UserEmail $email): ?User;
}
