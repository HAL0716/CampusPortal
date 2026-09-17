<?php

namespace App\Domain\Course\Exceptions;

use App\Domain\Exceptions\DomainException;

final class CourseIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Course ID is not assigned.');
    }
}
