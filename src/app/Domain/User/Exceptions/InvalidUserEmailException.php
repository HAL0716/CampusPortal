<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class InvalidUserEmailException extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = 'メールアドレスが不正な形式です。';

    public function __construct()
    {
        parent::__construct('Invalid email address.');
    }
}
