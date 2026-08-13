<?php

namespace App\Domain\Enrollment\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class EnrollmentAlreadyExistsException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Enrollment already exists.');
    }

    public function userMessage(): string
    {
        return '履修は登録済みです。';
    }
}
