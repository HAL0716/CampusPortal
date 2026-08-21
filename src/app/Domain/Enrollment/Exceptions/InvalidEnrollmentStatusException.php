<?php

namespace App\Domain\Enrollment\Exceptions;

use App\Domain\Exceptions\InvalidStatusException;

final class InvalidEnrollmentStatusException extends InvalidStatusException
{
    public function __construct()
    {
        parent::__construct('Invalid enrollment status.');
    }
}
