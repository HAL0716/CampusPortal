<?php

namespace App\Domain\Student\Exceptions;

use RuntimeException;

final class StudentNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Student not found.');
    }
}
