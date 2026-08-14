<?php

namespace App\Application\Services\Authentication\UseCases;

use App\Application\Security\PasswordHasherInterface;
use App\Application\Services\Authentication\AuthenticationService;
use App\Application\Services\Authentication\Commands\LoginCommand;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserEmail;

final class LoginUseCase
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly AuthenticationService $auth,
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
