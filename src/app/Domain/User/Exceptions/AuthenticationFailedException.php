<?php

namespace App\Domain\User\Exceptions;

use RuntimeException;

final class AuthenticationFailedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Authentication failed.');
    }
}
