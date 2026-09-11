<?php

namespace Tests\Support\TestHelpers;

use App\Domain\User\Entities\User;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserPassword;

trait UserTestHelper
{
    use IdTestHelper;

    protected function userName(): string
    {
        return 'Test User';
    }

    protected function userEmail(?string $email = null): UserEmail
    {
        return new UserEmail($email ?? 'test@example.com');
    }

    protected function userPassword(?string $password = null): UserPassword
    {
        return UserPassword::create($password ?? 'pass1234');
    }

    protected function hashedUserPassword(?string $password = null): UserPassword
    {
        return UserPassword::fromHash(
            $password ?? '$2y$04$KPSmno5kdzCzeERbPvLGW.oehD.NdNf7Dlr2J65lYium3zHWvDZBO'
        );
    }

    protected function createUser(
        ?string $email = null,
        ?string $password = null,
        ?string $name = null,
        bool $hashed = false,
    ): User {
        return User::create(
            email: $this->userEmail($email),
            password: $hashed
                ? $this->hashedUserPassword($password)
                : $this->userPassword($password),
            name: $name ?? 'Test User',
        );
    }

    protected function reconstructUser(
        ?int $id = null,
        ?string $email = null,
        ?string $password = null,
        ?string $name = null,
        ?UserStatus $status = null,
    ): User {
        return User::reconstruct(
            id: $this->userId($id),
            email: $this->userEmail($email),
            password: $this->hashedUserPassword($password),
            name: $name ?? 'Test User',
            status: $status ?? UserStatus::ACTIVE,
        );
    }
}
