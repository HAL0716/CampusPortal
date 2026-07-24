<?php

namespace App\Domain\Teacher\Exceptions;

use RuntimeException;

final class TeacherNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Teacher not found.');
    }
}
