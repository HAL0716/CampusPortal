<?php

namespace App\Application\Contexts\Authentication\UseCases;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\Authentication\Commands\LoginCommand;
use App\Application\Services\Security\PasswordHasher;
use App\Domain\Authentication\Exceptions\AuthenticationFailedException;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserEmail;

final class LoginUseCase
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly AuthenticationService $auth,
        private readonly PasswordHasher $hasher
    ) {}

    public function execute(LoginCommand $command): void
    {
        $user = $this->users->findByEmail(new UserEmail($command->email));

        if (! $user || ! $this->hasher->verify($command->password, $user->password()->value())) {
            throw new AuthenticationFailedException;
        }

        $this->auth->login($user);
    }
}
