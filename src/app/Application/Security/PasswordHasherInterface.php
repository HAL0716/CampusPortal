<?php

namespace App\Application\Security;

interface PasswordHasherInterface
{
    public function hash(string $password): string;

    public function verify(string $plain, string $hashed): bool;
}
