<?php

namespace App\Domain\User\Exceptions;

use LogicException;

final class UserIdAlreadyAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('User ID is already assigned.');
    }
}
