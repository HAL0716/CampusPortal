<?php

namespace App\Domain\User\Exceptions;

use App\Domain\Exceptions\DomainException;

final class UserIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('User ID is not assigned.');
    }
}
