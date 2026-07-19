<?php

namespace Tests\Unit\Infrastructure\Database;

use App\Application\User\UserDuplicateTarget;
use App\Infrastructure\Database\Sqlite\SqliteUserDuplicateDetector;
use Illuminate\Database\QueryException;
use PDOException;
use Tests\TestCase;

final class SqliteUserDuplicateDetectorTest extends TestCase
{
    private SqliteUserDuplicateDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->detector = new SqliteUserDuplicateDetector;
    }

    public function test_returns_true_when_user_email_duplicate_constraint_is_violated(): void
    {
        $exception = $this->queryException(
            19,
            'UNIQUE constraint failed: users.email'
        );

        $this->assertTrue(
            $this->detector->isDuplicate(
                $exception,
                UserDuplicateTarget::EMAIL,
            )
        );
    }

    public function test_returns_false_when_duplicate_constraint_is_not_user_constraint(): void
    {
        $exception = $this->queryException(
            19,
            'UNIQUE constraint failed: posts.slug'
        );

        $this->assertFalse(
            $this->detector->isDuplicate(
                $exception,
                UserDuplicateTarget::EMAIL,
            )
        );
    }

    public function test_returns_false_when_error_is_not_duplicate_constraint(): void
    {
        $exception = $this->queryException(
            5,
            'FOREIGN KEY constraint failed',
        );

        $this->assertFalse(
            $this->detector->isDuplicate(
                $exception,
                UserDuplicateTarget::EMAIL,
            )
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
            'sqlite',
            'insert into users',
            [],
            $pdoException,
        );
    }
}
