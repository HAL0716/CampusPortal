<?php

namespace App\Domain\Enrollment\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class EnrollmentNotFoundException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Enrollment not found.');
    }

    public function userMessage(): string
    {
        return '履修情報がありません。';
    }
}
