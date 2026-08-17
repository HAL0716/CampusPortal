<?php

namespace App\Application\Contexts\Authentication\Commands;

final readonly class LoginCommand
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
