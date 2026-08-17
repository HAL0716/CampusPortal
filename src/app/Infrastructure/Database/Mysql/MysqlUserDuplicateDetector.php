<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\Contexts\User\Duplicate\UserDuplicateDetector;
use App\Application\Contexts\User\Duplicate\UserDuplicateTarget;
use UnitEnum;

final class MysqlUserDuplicateDetector extends AbstractMysqlDuplicateDetector implements UserDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            UserDuplicateTarget::EMAIL => 'users_email_unique',
            default => null,
        };
    }
}
