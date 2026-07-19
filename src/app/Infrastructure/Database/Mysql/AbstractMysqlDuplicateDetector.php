<?php

namespace App\Infrastructure\Database\Mysql;

use App\Infrastructure\Database\AbstractDuplicateDetector;

abstract class AbstractMysqlDuplicateDetector extends AbstractDuplicateDetector
{
    private const MYSQL_DUPLICATE_ENTRY = 1062;

    final protected function duplicateErrorCode(): int
    {
        return self::MYSQL_DUPLICATE_ENTRY;
    }
}
