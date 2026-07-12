<?php

namespace App\Domain\User\Exceptions;

use RuntimeException;

final class UserNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User not found.');
    }
}
