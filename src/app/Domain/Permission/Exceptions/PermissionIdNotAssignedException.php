<?php

namespace App\Domain\Permission\Exceptions;

use App\Domain\Exceptions\DomainException;

final class PermissionIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Permission ID is not assigned.');
    }
}
