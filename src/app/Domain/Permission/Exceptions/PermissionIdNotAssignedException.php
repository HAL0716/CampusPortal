<?php

namespace App\Domain\Permission\Exceptions;

use LogicException;

final class PermissionIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Permission ID is not assigned.');
    }
}
