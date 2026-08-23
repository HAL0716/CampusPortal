<?php

namespace App\Domain\Semester\Exceptions;

use App\Domain\Exceptions\DomainException;

final class SemesterIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Semester ID is not assigned.');
    }
}
