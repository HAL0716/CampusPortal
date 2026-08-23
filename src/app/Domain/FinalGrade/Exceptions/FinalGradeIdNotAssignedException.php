<?php

namespace App\Domain\FinalGrade\Exceptions;

use App\Domain\Exceptions\DomainException;

final class FinalGradeIdNotAssignedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Final Grade ID is not assigned.');
    }
}
