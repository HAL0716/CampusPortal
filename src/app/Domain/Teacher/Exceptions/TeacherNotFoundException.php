<?php

namespace App\Domain\Teacher\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class TeacherNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = '教員情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Teacher not found.');
    }
}
