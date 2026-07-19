<?php

namespace App\Infrastructure\Database\Sqlite;

use App\Infrastructure\Database\AbstractDuplicateDetector;

abstract class AbstractSqliteDuplicateDetector extends AbstractDuplicateDetector
{
    private const SQLITE_CONSTRAINT = 19;

    final protected function duplicateErrorCode(): int
    {
        return self::SQLITE_CONSTRAINT;
    }
}
