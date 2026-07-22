<?php

namespace App\Domain\Semester\Exceptions;

use RuntimeException;

final class SemesterNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Semester not found.');
    }
}
