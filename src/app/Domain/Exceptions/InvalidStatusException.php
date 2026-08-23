<?php

namespace App\Domain\Exceptions;

abstract class InvalidStatusException extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = '不正なステータスです。';

    public function statusCode(): int
    {
        return 422;
    }
}
