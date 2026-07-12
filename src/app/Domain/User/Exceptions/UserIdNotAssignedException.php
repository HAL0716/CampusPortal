<?php

namespace App\Domain\User\Exceptions;

use LogicException;

final class UserIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('User ID is not assigned.');
    }
}
