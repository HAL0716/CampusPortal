<?php

namespace App\Domain\Teacher\Exceptions;

use LogicException;

final class TeacherIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Teacher ID is not assigned.');
    }
}
