<?php

namespace App\Domain\Semester\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class SemesterNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = '学期情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Semester not found.');
    }
}
