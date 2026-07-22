<?php

namespace App\Domain\Semester\Exceptions;

use LogicException;

final class SemesterIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Semester ID is not assigned.');
    }
}
