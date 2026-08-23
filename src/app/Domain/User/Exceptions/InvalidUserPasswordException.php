<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class InvalidUserPasswordException extends DomainException
{
    protected const DEFAULT_USER_MESSAGE = 'パスワードが不正な形式です。';

    public function __construct()
    {
        parent::__construct('Invalid password.');
    }
}
