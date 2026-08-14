<?php

namespace App\Application\Authentication;

use App\Application\Services\Authentication\AuthenticationService;

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
