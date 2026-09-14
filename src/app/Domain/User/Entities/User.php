<?php

namespace App\Domain\User\Entities;

use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Exceptions\UserIdNotAssignedException;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserPassword;

final class User
{
    private function __construct(
        private ?UserId $id,
        private UserEmail $email,
        private UserPassword $password,
        private string $name,
        private UserStatus $status,
    ) {}

    public static function create(UserEmail $email, UserPassword $password, string $name): self
    {
        return new self(null, $email, $password, $name, UserStatus::ACTIVE);
    }

    public static function reconstruct(UserId $id, UserEmail $email, UserPassword $password, string $name, UserStatus $status): self
    {
        return new self($id, $email, $password, $name, $status);
    }

    public function deactivate(): self
    {
        return new self($this->id, $this->email, $this->password, $this->name, UserStatus::INACTIVE);
    }

    public function canLogin(): bool
    {
        return $this->status === UserStatus::ACTIVE;
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

    public function status(): UserStatus
    {
        return $this->status;
    }
}
