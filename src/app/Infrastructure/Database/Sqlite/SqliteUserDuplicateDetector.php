<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\User\Duplicate\UserDuplicateDetector;
use App\Application\Contexts\User\Duplicate\UserDuplicateTarget;
use UnitEnum;

final class SqliteUserDuplicateDetector extends AbstractSqliteDuplicateDetector implements UserDuplicateDetector
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            UserDuplicateTarget::EMAIL => 'users.email',
            default => null,
        };
    }
}
