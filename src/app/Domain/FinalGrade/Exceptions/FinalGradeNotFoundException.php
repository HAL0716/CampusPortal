<?php

namespace App\Domain\FinalGrade\Exceptions;

use RuntimeException;

final class FinalGradeNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Final grade not found.');
    }
}
