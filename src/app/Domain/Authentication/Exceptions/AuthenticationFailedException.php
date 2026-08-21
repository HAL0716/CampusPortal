<?php

namespace App\Domain\Authentication\Exceptions;

use RuntimeException;

final class AuthenticationFailedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('メールアドレスまたはパスワードが違います。');
    }
}
