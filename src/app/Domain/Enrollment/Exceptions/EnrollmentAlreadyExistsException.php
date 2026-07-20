<?php

namespace App\Domain\Enrollment\Exceptions;

use RuntimeException;

final class EnrollmentAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Enrollment already exists.');
    }
}
