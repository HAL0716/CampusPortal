<?php

namespace App\Infrastructure\Authentication;

use App\Application\Services\Authentication\AuthenticationService;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserId;
use Illuminate\Support\Facades\Auth;

final class LaravelAuthenticationService implements AuthenticationService
{
    private ?User $cachedUser = null;

    private bool $userResolved = false;

    public function __construct(
        private UserRepository $users,
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
        if ($this->userResolved) {
            return $this->cachedUser;
        }

        $this->cachedUser = $this->resolveUser();
        $this->userResolved = true;

        return $this->cachedUser;
    }

    public function requireUser(): User
    {
        return $this->user() ?? throw new AuthenticationFailedException;
    }

    private function resolveUser(): ?User
    {
        $id = Auth::id();

        if ($id === null) {
            return null;
        }

        $user = $this->users->findById(new UserId((int) $id));

        if ($user !== null) {
            return $user;
        }

        Auth::logout();

        return null;
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
