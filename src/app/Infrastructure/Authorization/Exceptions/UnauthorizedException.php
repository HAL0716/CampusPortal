<?php

namespace App\Infrastructure\Authorization\Exceptions;

use RuntimeException;

final class UnauthorizedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Unauthorized');
    }
}
