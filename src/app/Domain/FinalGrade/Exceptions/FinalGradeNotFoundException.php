<?php

namespace App\Domain\FinalGrade\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class FinalGradeNotFoundException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('Final grade not found.');
    }

    public function userMessage(): string
    {
        return '最終成績情報がありません。';
    }
}
