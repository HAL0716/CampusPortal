<?php

namespace App\Domain\User;

use App\Domain\User\Exceptions\UserIdAlreadyAssignedException;
use App\Domain\User\Exceptions\UserIdNotAssignedException;

final class User
{
    private function __construct(
        private ?UserId $id,
        private UserEmail $email,
        private string $password,
        private string $name,
    ) {}

    public static function create(UserEmail $email, string $password, string $name): self
    {
        return new self(null, $email, $password, $name);
    }

    public static function reconstruct(UserId $id, UserEmail $email, string $password, string $name): self
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

    public function assignId(UserId $id): void
    {
        if ($this->id !== null) {
            throw new UserIdAlreadyAssignedException;
        }

        $this->id = $id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function name(): string
    {
        return $this->name;
    }
}
