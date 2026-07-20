<?php

namespace App\Application\Authentication;

final readonly class LoginCommand
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
