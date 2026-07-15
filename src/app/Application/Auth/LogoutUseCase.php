<?php

namespace App\Application\Auth;

final class LogoutUseCase
{
    public function __construct(
        private readonly AuthenticationServiceInterface $auth
    ) {}

    public function execute(): void
    {
        $this->auth->logout();
    }
}
