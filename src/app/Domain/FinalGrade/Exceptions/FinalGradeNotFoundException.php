<?php

namespace App\Domain\FinalGrade\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class FinalGradeNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = '最終成績情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Final grade not found.');
    }
}
