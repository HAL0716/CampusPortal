<?php

namespace Tests\Support\User;

trait UserTestData
{
    protected function userId(): int
    {
        return 1;
    }

    protected function userName(): string
    {
        return 'Test User';
    }

    protected function userEmail(): string
    {
        return 'test@example.com';
    }

    protected function invalidUserEmail(): string
    {
        return 'invalid-email';
    }

    protected function userPassword(): string
    {
        return 'pass1234';
    }

    protected function hashedUserPassword(): string
    {
        return '$2y$04$KPSmno5kdzCzeERbPvLGW.oehD.NdNf7Dlr2J65lYium3zHWvDZBO';
    }

    protected function invalidUserPassword(): string
    {
        return 'short';
    }
}
