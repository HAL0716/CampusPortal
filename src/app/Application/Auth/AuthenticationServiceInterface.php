<?php

namespace App\Application\Auth;

use App\Domain\User\User;

interface AuthenticationServiceInterface
{
    public function login(User $user): void;

    public function logout(): void;

    public function user(): ?User;
}
