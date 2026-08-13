<?php

namespace App\Domain\Student\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class StudentNotFoundException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Student not found.');
    }

    public function userMessage(): string
    {
        return '学生情報がありません。';
    }
}
