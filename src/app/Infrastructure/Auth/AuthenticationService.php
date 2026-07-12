<?php

namespace App\Infrastructure\Auth;

use App\Application\Auth\AuthenticationServiceInterface;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\User;
use Illuminate\Support\Facades\Auth;

final class AuthenticationService implements AuthenticationServiceInterface
{
    public function login(User $user): void
    {
        if (! Auth::loginUsingId($user->requireId()->value())) {
            throw new AuthenticationFailedException;
        }
    }
}
