<?php

namespace App\Application\Auth;

use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

final class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {}

    public function execute(LoginCommand $command): void
    {
        $user = $this->users->findByEmail($command->email);

        if (! $user || ! $user->verifyPassword($command->password)) {
            throw new AuthenticationFailedException;
        }

        Auth::loginUsingId($user->id()->value());
    }
}
