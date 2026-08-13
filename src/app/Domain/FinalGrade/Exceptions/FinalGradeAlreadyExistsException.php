<?php

namespace App\Domain\FinalGrade\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class FinalGradeAlreadyExistsException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Final grade already exists.');
    }

    public function userMessage(): string
    {
        return '最終成績は登録済みです。';
    }
}
