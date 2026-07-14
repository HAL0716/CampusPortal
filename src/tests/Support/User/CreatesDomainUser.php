<?php

namespace Tests\Support\User;

use App\Domain\User\User;
use App\Domain\User\UserEmail;
use App\Domain\User\UserId;
use App\Domain\User\UserPassword;

trait CreatesDomainUser
{
    use UserTestData;

    protected function userIdValueObject(?int $id = null): UserId
    {
        return new UserId($id ?? $this->userId());
    }

    protected function userEmailValueObject(?string $email = null): UserEmail
    {
        return new UserEmail($email ?? $this->userEmail());
    }

    protected function userPasswordValueObject(?string $password = null): UserPassword
    {
        return UserPassword::create(
            $password ?? $this->userPassword()
        );
    }

    protected function hashedUserPasswordValueObject(?string $password = null): UserPassword
    {
        return UserPassword::fromHash(
            $password ?? $this->hashedUserPassword()
        );
    }

    protected function createUser(
        ?string $email = null,
        ?string $password = null,
        ?string $name = null,
        bool $hashed = false,
    ): User {
        return User::create(
            email: $this->userEmailValueObject($email),
            password: $hashed
                ? $this->hashedUserPasswordValueObject($password)
                : $this->userPasswordValueObject($password),
            name: $name ?? $this->userName(),
        );
    }

    protected function reconstructUser(
        ?int $id = null,
        ?string $email = null,
        ?string $password = null,
        ?string $name = null,
    ): User {
        return User::reconstruct(
            id: $this->userIdValueObject($id),
            email: $this->userEmailValueObject($email),
            password: $this->hashedUserPasswordValueObject($password),
            name: $name ?? $this->userName(),
        );
    }
}
