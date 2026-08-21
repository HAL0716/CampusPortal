<?php

namespace App\Domain\Student\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class StudentNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = '学生情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Student not found.');
    }
}
