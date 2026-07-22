<?php

namespace App\Domain\Enrollment\Exceptions;

use RuntimeException;

final class EnrollmentNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Enrollment not found.');
    }
}
