<?php

namespace App\Domain\Exceptions;

use RuntimeException;

abstract class DomainException extends RuntimeException
{
    protected const DEFAULT_USER_MESSAGE = '処理に失敗しました。もう一度お試しください。';

    public function statusCode(): int
    {
        return 500;
    }

    public function userMessage(): string
    {
        return static::DEFAULT_USER_MESSAGE;
    }
}
