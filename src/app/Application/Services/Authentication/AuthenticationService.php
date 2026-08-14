<?php

namespace App\Application\Services\Authentication;

use App\Domain\User\Entities\User;

interface AuthenticationService
{
    public function login(User $user): void;

    public function logout(): void;

    public function user(): ?User;

    public function requireUser(): User;
}
