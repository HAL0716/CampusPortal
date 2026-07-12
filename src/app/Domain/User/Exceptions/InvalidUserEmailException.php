<?php

namespace App\Domain\User\Exceptions;

use InvalidArgumentException;

class InvalidUserEmailException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid email address.');
    }
}
