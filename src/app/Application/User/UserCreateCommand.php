<?php

namespace App\Application\User;

final readonly class UserCreateCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name
    ) {}
}
