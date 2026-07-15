<?php

namespace App\Domain\User;

use App\Domain\User\Exceptions\UserIdNotAssignedException;

final class User
{
    private function __construct(
        private ?UserId $id,
        private UserEmail $email,
        private UserPassword $password,
        private string $name,
    ) {}

    public static function create(UserEmail $email, UserPassword $password, string $name): self
    {
        return new self(null, $email, $password, $name);
    }

    public static function reconstruct(UserId $id, UserEmail $email, UserPassword $password, string $name): self
    {
        return new self($id, $email, $password, $name);
    }

    public function id(): ?UserId
    {
        return $this->id;
    }

    public function requireId(): UserId
    {
        if ($this->id === null) {
            throw new UserIdNotAssignedException;
        }

        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function name(): string
    {
        return $this->name;
    }
}
