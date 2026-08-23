<?php

namespace App\Domain\User\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = 'ユーザー情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('User not found.');
    }
}
