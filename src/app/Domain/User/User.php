<?php

namespace App\Domain\User;

use App\Domain\User\Exceptions\UserIdNotAssignedException;

final class User
{
    private function __construct(
        private ?UserId $id,
        private string $email,
        private string $password,
        private string $name,
    ) {}

    public static function create(string $email, string $password, string $name): self
    {
        return new self(null, $email, $password, $name);
    }

    public static function reconstruct(UserId $id, string $email, string $password, string $name): self
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

    public function email(): string
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
