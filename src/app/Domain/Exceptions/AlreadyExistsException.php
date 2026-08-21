<?php

namespace App\Domain\Exceptions;

abstract class AlreadyExistsException extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = 'データが既にあります。';

    public function statusCode(): int
    {
        return 409;
    }
}
