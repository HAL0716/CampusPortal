<?php

namespace App\Domain\FinalGrade\Exceptions;

use LogicException;

final class FinalGradeIdNotAssignedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Final Grade ID is not assigned.');
    }
}
