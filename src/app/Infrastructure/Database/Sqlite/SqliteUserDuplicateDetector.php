<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Application\Contexts\User\UserDuplicateDetectorInterface;
use App\Application\Contexts\User\UserDuplicateTarget;
use UnitEnum;

final class SqliteUserDuplicateDetector extends AbstractSqliteDuplicateDetector implements UserDuplicateDetectorInterface
{
    protected function constraint(UnitEnum $target): ?string
    {
        return match ($target) {
            UserDuplicateTarget::EMAIL => 'users.email',
            default => null,
        };
    }
}
