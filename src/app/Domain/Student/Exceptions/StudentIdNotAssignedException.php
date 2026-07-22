<?php

namespace App\Domain\Student\Exceptions;

use LogicException;

final class StudentIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Student ID is not assigned.');
    }
}
