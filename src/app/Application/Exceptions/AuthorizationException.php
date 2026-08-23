<?php

namespace App\Application\Exceptions;

use RuntimeException;

abstract class AuthorizationException extends RuntimeException
{
    protected const DEFAULT_USER_MESSAGE = '権限がありません。';

    public function statusCode(): int
    {
        return 403;
    }

    public function userMessage(): string
    {
        return static::DEFAULT_USER_MESSAGE;
    }
}
