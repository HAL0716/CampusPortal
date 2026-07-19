<?php

namespace Tests\Unit\Infrastructure\Database\Sqlite;

use Tests\Unit\Infrastructure\Database\DuplicateDetectorTestCase;

abstract class SqliteDuplicateDetectorTestCase extends DuplicateDetectorTestCase
{
    protected function connection(): string
    {
        return 'sqlite';
    }

    protected function duplicateErrorCode(): int
    {
        return 19;
    }
}
