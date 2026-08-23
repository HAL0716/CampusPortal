<?php

namespace App\Domain\Enrollment\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class EnrollmentNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = '履修情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Enrollment not found.');
    }
}
