<?php

namespace App\Domain\Student\Exceptions;

use DomainException;

class InvalidStudentNumberException extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = '学生番号が不正な形式です。';

    public function __construct()
    {
        parent::__construct('Invalid student number.');
    }
}
