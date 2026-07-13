<?php

namespace App\Infrastructure\Auth;

use App\Application\Auth\AuthenticationServiceInterface;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

final class AuthenticationService implements AuthenticationServiceInterface
{
    private ?User $cachedUser = null;

    private bool $userResolved = false;

    public function __construct(
        private UserRepositoryInterface $users
    ) {}

    public function login(User $user): void
    {
        if (! Auth::loginUsingId($user->requireId()->value())) {
            throw new AuthenticationFailedException;
        }

        $this->cacheUser($user);
    }

    public function logout(): void
    {
        Auth::logout();

        $this->forgetUser();
    }

    public function user(): ?User
    {
        if (! $this->userResolved) {
            $this->cachedUser = $this->resolveUser();
            $this->userResolved = true;
        }

        return $this->cachedUser;
    }

    private function resolveUser(): ?User
    {
        $id = Auth::id();

        if ($id === null) {
            return null;
        }

        return $this->users->findById(new UserId((int) $id));
    }

    private function cacheUser(User $user): void
    {
        $this->cachedUser = $user;
        $this->userResolved = true;
    }

    private function forgetUser(): void
    {
        $this->cachedUser = null;
        $this->userResolved = false;
    }
}
