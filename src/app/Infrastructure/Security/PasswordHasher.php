<?php

namespace App\Infrastructure\Security;

use App\Application\Security\PasswordHasherInterface;
use Illuminate\Support\Facades\Hash;

class PasswordHasher implements PasswordHasherInterface
{
    public function hash(string $password): string
    {
        return Hash::make($password);
    }

    public function verify(string $plain, string $hashed): bool
    {
        return Hash::check($plain, $hashed);
    }
}
