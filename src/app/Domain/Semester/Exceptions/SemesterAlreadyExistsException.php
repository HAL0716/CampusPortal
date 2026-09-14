<?php

namespace App\Domain\Semester\Exceptions;

use App\Domain\Exceptions\AlreadyExistsException;

final class SemesterAlreadyExistsException extends AlreadyExistsException
{
    protected const DEFAULT_USER_MESSAGE = '学期情報は既に存在します。';

    public function __construct()
    {
        parent::__construct('Semester already exists.');
    }
}
