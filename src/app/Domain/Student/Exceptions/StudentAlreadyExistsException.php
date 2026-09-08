<?php

namespace App\Domain\Student\Exceptions;

use App\Domain\Exceptions\AlreadyExistsException;

final class StudentAlreadyExistsException extends AlreadyExistsException
{
    protected const DEFAULT_USER_MESSAGE = '学生は既に存在します。';

    public function __construct()
    {
        parent::__construct('Student already exists.');
    }
}
