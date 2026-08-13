<?php

namespace App\Domain\User\Exceptions;

use App\Exceptions\UserMessageException;
use RuntimeException;

final class UserAlreadyExistsException extends RuntimeException implements UserMessageException
{
    public function __construct()
    {
        parent::__construct('User already exists.');
    }

    public function userMessage(): string
    {
        return 'ユーザーは既に存在します。';
    }
}
