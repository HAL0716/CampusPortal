<?php

namespace App\Infrastructure\Exceptions;

use RuntimeException;

abstract class InfrastructureException extends RuntimeException
{
    protected const DEFAULT_USER_MESSAGE = '処理を完了できませんでした。';

    public function statusCode(): int
    {
        return 500;
    }

    public function userMessage(): string
    {
        return static::DEFAULT_USER_MESSAGE;
    }
}
