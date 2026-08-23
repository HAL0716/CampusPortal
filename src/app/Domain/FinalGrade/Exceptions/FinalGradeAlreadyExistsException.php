<?php

namespace App\Domain\FinalGrade\Exceptions;

use App\Domain\Exceptions\AlreadyExistsException;

final class FinalGradeAlreadyExistsException extends AlreadyExistsException
{
    protected const DEFAULT_USER_MESSAGE = '最終成績は既に存在します。';

    public function __construct()
    {
        parent::__construct('Final grade already exists.');
    }
}
