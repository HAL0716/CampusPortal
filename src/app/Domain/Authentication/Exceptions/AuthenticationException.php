<?php

namespace App\Domain\Authentication\Exceptions;

use RuntimeException;

abstract class AuthenticationException extends RuntimeException
{
    protected const DEFAULT_USER_MESSAGE = '認証に失敗しました。';

    public function statusCode(): int
    {
        return 401;
    }

    public function userMessage(): string
    {
        return static::DEFAULT_USER_MESSAGE;
    }
}
