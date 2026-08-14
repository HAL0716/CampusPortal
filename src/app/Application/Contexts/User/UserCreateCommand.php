<?php

namespace App\Application\Contexts\User;

final readonly class UserCreateCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name
    ) {}
}
