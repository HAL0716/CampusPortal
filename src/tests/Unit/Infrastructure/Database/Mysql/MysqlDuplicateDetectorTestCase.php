<?php

namespace Tests\Unit\Infrastructure\Database\Mysql;

use Tests\Unit\Infrastructure\Database\DuplicateDetectorTestCase;

abstract class MysqlDuplicateDetectorTestCase extends DuplicateDetectorTestCase
{
    protected function connection(): string
    {
        return 'mysql';
    }

    protected function duplicateErrorCode(): int
    {
        return 1062;
    }
}
