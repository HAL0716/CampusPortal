<?php

namespace App\Domain\Role\Exceptions;

use App\Domain\Exceptions\NotFoundException;

final class RoleNotFoundException extends NotFoundException
{
    protected const DEFAULT_USER_MESSAGE = 'ロール情報が見つかりません。';

    public function __construct()
    {
        parent::__construct('Role not found.');
    }
}
