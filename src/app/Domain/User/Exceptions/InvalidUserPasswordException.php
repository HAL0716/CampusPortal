<?php

namespace App\Domain\User\Exceptions;

use InvalidArgumentException;

class InvalidUserPasswordException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid password.');
    }
}
