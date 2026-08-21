<?php

namespace App\Domain\User\Exceptions;

use App\Domain\Exceptions\AlreadyExistsException;

final class UserAlreadyExistsException extends AlreadyExistsException
{
    protected const DEFAULT_USER_MESSAGE = 'ユーザーは既に存在します。';

    public function __construct()
    {
        parent::__construct('User already exists.');
    }
}
