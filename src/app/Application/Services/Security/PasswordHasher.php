<?php

namespace App\Application\Services\Security;

interface PasswordHasher
{
    public function hash(string $password): string;

    public function verify(string $plain, string $hashed): bool;
}
