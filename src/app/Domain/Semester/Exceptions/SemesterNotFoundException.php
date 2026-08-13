<?php

namespace App\Domain\Semester\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class SemesterNotFoundException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Semester not found.');
    }

    public function userMessage(): string
    {
        return '学期情報がありません。';
    }
}
