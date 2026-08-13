<?php

namespace App\Domain\User\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class UserNotFoundException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('User not found.');
    }

    public function userMessage(): string
    {
        return 'ユーザー情報がありません。';
    }
}
