<?php

namespace App\Domain\CourseOffering\Exceptions;

use App\Domain\Exceptions\DomainException;

final class CourseOfferingIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('CourseOffering ID is not assigned.');
    }
}
