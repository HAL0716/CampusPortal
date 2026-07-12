<?php

namespace App\Domain\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
}
