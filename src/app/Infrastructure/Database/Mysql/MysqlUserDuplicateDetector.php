<?php

namespace App\Infrastructure\Database\Mysql;

use App\Application\User\UserDuplicateDetectorInterface;
use App\Application\User\UserDuplicateTarget;
use UnitEnum;

final class MysqlUserDuplicateDetector extends AbstractMysqlDuplicateDetector implements UserDuplicateDetectorInterface
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            UserDuplicateTarget::EMAIL => 'users_email_unique',
            default => null,
        };
    }
}
