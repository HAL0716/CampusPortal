<?php

namespace App\Domain\FinalGrade\Exceptions;

use RuntimeException;

final class FinalGradeAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Final grade already exists.');
    }
}
