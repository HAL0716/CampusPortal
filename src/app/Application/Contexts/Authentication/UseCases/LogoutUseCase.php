<?php

namespace App\Application\Contexts\Authentication\UseCases;

use App\Application\Contexts\Authentication\AuthenticationService;

final class LogoutUseCase
{
    public function __construct(
        private readonly AuthenticationService $auth
    ) {}

    public function execute(): void
    {
        $this->auth->logout();
    }
}
