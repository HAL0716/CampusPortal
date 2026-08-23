<?php

namespace App\Domain\Student\Exceptions;

use App\Domain\Exceptions\DomainException;

final class StudentIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Student ID is not assigned.');
    }
}
