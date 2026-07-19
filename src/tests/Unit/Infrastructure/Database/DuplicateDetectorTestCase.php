<?php

namespace Tests\Unit\Infrastructure\Database;

use App\Application\Database\DuplicateDetectorInterface;
use Illuminate\Database\QueryException;
use PDOException;
use Tests\TestCase;
use UnitEnum;

abstract class DuplicateDetectorTestCase extends TestCase
{
    abstract protected function detector(): DuplicateDetectorInterface;

    abstract protected function connection(): string;

    abstract protected function duplicateErrorCode(): int;

    protected function assertDuplicate(
        UnitEnum $target,
        string $message,
        bool $expected,
        ?int $errorCode = null,
    ): void {
        $exception = $this->queryException(
            $errorCode ?? $this->duplicateErrorCode(),
            $message,
        );

        $this->assertSame(
            $expected,
            $this->detector()->isDuplicate(
                $exception,
                $target,
            ),
        );
    }

    private function queryException(
        int $code,
        string $message,
    ): QueryException {
        $pdoException = new PDOException($message);

        $pdoException->errorInfo = [
            '23000',
            $code,
            $message,
        ];

        return new QueryException(
            $this->connection(),
            '',
            [],
            $pdoException,
        );
    }
}
