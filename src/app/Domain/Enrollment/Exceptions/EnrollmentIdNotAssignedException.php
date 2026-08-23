<?php

namespace App\Domain\Enrollment\Exceptions;

use App\Domain\Exceptions\DomainException;

final class EnrollmentIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Enrollment ID is not assigned.');
    }
}
