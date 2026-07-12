<?php

namespace App\Domain\User;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function findByEmail(string $email): ?User;
}
