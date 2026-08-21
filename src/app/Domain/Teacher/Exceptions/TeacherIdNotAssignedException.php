<?php

namespace App\Domain\Teacher\Exceptions;

use App\Domain\Exceptions\DomainException;

final class TeacherIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Teacher ID is not assigned.');
    }
}
