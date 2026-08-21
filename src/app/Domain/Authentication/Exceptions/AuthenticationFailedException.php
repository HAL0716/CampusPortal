<?php

namespace App\Domain\Authentication\Exceptions;

final class AuthenticationFailedException extends AuthenticationException
{
    protected const DEFAULT_USER_MESSAGE = 'メールアドレスまたはパスワードが違います。';

    public function __construct()
    {
        parent::__construct('Authentication failed.');
    }
}
