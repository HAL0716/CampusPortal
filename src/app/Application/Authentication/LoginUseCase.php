<?php

namespace App\Application\Authentication;

use App\Application\Security\PasswordHasherInterface;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\UserEmail;
use App\Domain\User\UserRepositoryInterface;

final class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly AuthenticationServiceInterface $auth,
        private readonly PasswordHasherInterface $hasher
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
