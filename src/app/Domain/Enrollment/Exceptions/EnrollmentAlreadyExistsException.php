<?php

namespace App\Domain\Enrollment\Exceptions;

use App\Domain\Exceptions\AlreadyExistsException;

final class EnrollmentAlreadyExistsException extends AlreadyExistsException
{
    protected const DEFAULT_USER_MESSAGE = '履修は既に存在します。';

    public function __construct()
    {
        parent::__construct('Enrollment already exists.');
    }
}
