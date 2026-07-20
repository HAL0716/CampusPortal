<?php

namespace App\Application\Authentication;

use App\Domain\User\User;

interface AuthenticationServiceInterface
{
    public function login(User $user): void;

    public function logout(): void;

    public function user(): ?User;
}
