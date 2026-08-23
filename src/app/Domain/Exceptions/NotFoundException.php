<?php

namespace App\Domain\Exceptions;

abstract class NotFoundException extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = 'データが見つかりません。';

    public function statusCode(): int
    {
        return 404;
    }
}
