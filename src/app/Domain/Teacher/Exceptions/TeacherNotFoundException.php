<?php

namespace App\Domain\Teacher\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class TeacherNotFoundException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Teacher not found.');
    }

    public function userMessage(): string
    {
        return '教員情報がありません。';
    }
}
