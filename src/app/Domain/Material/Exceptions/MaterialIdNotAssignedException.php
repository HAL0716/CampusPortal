<?php

namespace App\Domain\Material\Exceptions;

use App\Domain\Exceptions\DomainException;

final class MaterialIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Material ID is not assigned.');
    }
}
