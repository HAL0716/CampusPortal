<?php

namespace App\Domain\Enrollment\Exceptions;

use LogicException;

final class EnrollmentIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Enrollment ID is not assigned.');
    }
}
