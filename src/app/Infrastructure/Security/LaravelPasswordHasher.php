<?php

namespace App\Infrastructure\Security;

use App\Application\Services\Security\PasswordHasher;
use Illuminate\Support\Facades\Hash;

class LaravelPasswordHasher implements PasswordHasher
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
