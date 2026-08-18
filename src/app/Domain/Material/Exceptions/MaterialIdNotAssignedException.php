<?php

namespace App\Domain\Material\Exceptions;

use LogicException;

final class MaterialIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Material ID is not assigned.');
    }
}
