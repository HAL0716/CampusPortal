<?php

namespace App\Domain\Enrollment\Exceptions;

use LogicException;

final class InvalidEnrollmentStatusException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Invalid enrollment status.');
    }
}
